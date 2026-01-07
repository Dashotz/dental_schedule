<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('main-site.auth.login');
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

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Log successful login
            \Log::info('Admin login successful', [
                'email' => $email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Redirect admins to admin dashboard
            return redirect()->intended(route('admin.dashboard'));
        }

        // Log failed login attempt (account lockout middleware will handle lockout)
        \Log::warning('Admin login failed', [
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
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

