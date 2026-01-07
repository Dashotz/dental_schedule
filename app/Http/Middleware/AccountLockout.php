<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AccountLockout
{
    /**
     * Handle an incoming request.
     * Locks account after multiple failed login attempts
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->input('email');
        $lockoutKey = 'login_attempts:' . md5($email . $request->ip());
        $maxAttempts = 5; // Maximum failed attempts
        $lockoutDuration = 900; // 15 minutes in seconds

        $attempts = Cache::get($lockoutKey, 0);

        if ($attempts >= $maxAttempts) {
            $remainingTime = Cache::get($lockoutKey . ':lockout_until', 0) - now()->timestamp;
            
            if ($remainingTime > 0) {
                $minutes = ceil($remainingTime / 60);
                \Log::warning('Account locked due to too many failed attempts', [
                    'email' => $email,
                    'ip' => $request->ip(),
                    'attempts' => $attempts,
                    'lockout_remaining_minutes' => $minutes,
                ]);

                return back()->withErrors([
                    'email' => "Too many failed login attempts. Please try again in {$minutes} minute(s).",
                ])->withInput($request->only('email'));
            } else {
                // Lockout expired, reset attempts
                Cache::forget($lockoutKey);
                Cache::forget($lockoutKey . ':lockout_until');
            }
        }

        try {
            $response = $next($request);
            
            // Check if login was successful by checking redirect location
            // Login controllers redirect to dashboard/admin on success
            $location = $response->headers->get('Location', '');
            if ($response->getStatusCode() === 302 && 
                (str_contains($location, 'dashboard') || str_contains($location, 'admin'))) {
                // Login successful, clear attempts
                Cache::forget($lockoutKey);
                Cache::forget($lockoutKey . ':lockout_until');
            } elseif ($email && $response->getStatusCode() !== 302) {
                // Login failed - increment attempts
                $attempts++;
                Cache::put($lockoutKey, $attempts, $lockoutDuration);
                
                if ($attempts >= $maxAttempts) {
                    Cache::put($lockoutKey . ':lockout_until', now()->timestamp + $lockoutDuration, $lockoutDuration);
                    \Log::warning('Account lockout triggered', [
                        'email' => $email,
                        'ip' => $request->ip(),
                        'attempts' => $attempts,
                    ]);
                }
            }
            
            return $response;
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Login failed - ValidationException thrown by login controller
            if ($email) {
                $attempts++;
                Cache::put($lockoutKey, $attempts, $lockoutDuration);
                
                if ($attempts >= $maxAttempts) {
                    Cache::put($lockoutKey . ':lockout_until', now()->timestamp + $lockoutDuration, $lockoutDuration);
                    \Log::warning('Account lockout triggered via exception', [
                        'email' => $email,
                        'ip' => $request->ip(),
                        'attempts' => $attempts,
                    ]);
                }
            }
            
            // Re-throw the exception
            throw $e;
        }
    }
}

