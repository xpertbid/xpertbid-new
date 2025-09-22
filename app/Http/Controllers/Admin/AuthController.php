<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Redirect to the creative login page
        return redirect()->route('login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back! You have been successfully logged in.');
        }

        return redirect()->back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput($request->except('password'));
    }

    /**
     * Show the registration form
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ], [
            'name.required' => 'Full name is required.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'terms.required' => 'You must accept the terms and conditions.',
            'terms.accepted' => 'You must accept the terms and conditions.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Account created successfully! Welcome to XpertBid Admin.');
    }

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // Update Google ID if not set
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
                
                Auth::login($user);
                
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back! You have been successfully logged in.');
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(),
                    'password' => Hash::make(Str::random(24)), // Random password for Google users
                ]);
                
                Auth::login($user);
                
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Account created successfully! Welcome to XpertBid Admin.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.login')
                ->withErrors(['google' => 'Unable to login with Google. Please try again.']);
        }
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'You have been successfully logged out.');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Handle forgot password request
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'No account found with this email address.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // For now, just show success message
        // In production, you would send a password reset email
        return redirect()->route('admin.login')
            ->with('success', 'If an account with that email exists, we have sent a password reset link.');
    }
}
