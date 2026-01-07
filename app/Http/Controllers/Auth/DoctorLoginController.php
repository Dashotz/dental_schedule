<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\UsesSubdomainViews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DoctorLoginController extends Controller
{
    use UsesSubdomainViews;
    public function showLoginForm()
    {
        return $this->subdomainView('auth.doctor-login');
    }

    public function login(Request $request)
    {
        // Sanitize inputs
        $email = filter_var(trim($request->email), FILTER_SANITIZE_EMAIL);
        
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/'],
        ], [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
        ]);
        
        // Use sanitized email
        $credentials['email'] = $email;

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Log successful login
            \Log::info('Doctor login successful', [
                'email' => $email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Redirect doctors to regular dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Log failed login attempt (account lockout middleware will handle lockout)
        \Log::warning('Doctor login failed', [
            'email' => $email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('doctor.login');
    }
}

