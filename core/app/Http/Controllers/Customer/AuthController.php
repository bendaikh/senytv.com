<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm(Request $request): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate') ?? 'templates.amber';
        return view("{$activeTemplate}.customer.login");
    }

    /**
     * Handle login
     */
    public function login(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('customer.dashboard'));
        }

        return redirect()->back()
            ->withErrors(['email' => 'These credentials do not match our records.'])
            ->withInput();
    }

    /**
     * Show registration form
     */
    public function showRegisterForm(Request $request): View
    {
        $activeTemplate = $request->attributes->get('activeTemplate') ?? 'templates.amber';
        $countries = json_decode(file_get_contents(storage_path('app/private/countries.json')), true);
        return view("{$activeTemplate}.customer.register", compact('countries'));
    }

    /**
     * Handle registration
     */
    public function register(Request $request): RedirectResponse
    {
        // Check if user with email already exists
        $existingUser = User::where('email', $request->email)->first();
        
        // If user exists and has a password, they're already registered
        if ($existingUser && $existingUser->password) {
            return redirect()->back()
                ->withErrors(['email' => 'This email is already registered. Please login instead.'])
                ->withInput();
        }

        // Validation rules
        $rules = [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country' => 'required|string|max:2',
            'password' => 'required|string|min:8|confirmed',
        ];

        // Phone number validation - only check uniqueness if provided and not null
        if ($request->filled('phone_number')) {
            $rules['phone_number'] = 'nullable|string|max:20|unique:users,phone_number';
        } else {
            $rules['phone_number'] = 'nullable|string|max:20';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If user exists (from payment) but no password, update them
        if ($existingUser) {
            $updateData = [
                'full_name' => $request->full_name,
                'country' => $request->country,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ];

            // Only update phone_number if it doesn't conflict
            if ($request->filled('phone_number')) {
                $phoneExists = User::where('phone_number', $request->phone_number)
                    ->where('id', '!=', $existingUser->id)
                    ->exists();
                
                if (!$phoneExists) {
                    $updateData['phone_number'] = $request->phone_number;
                }
                // If phone exists for another user, just skip updating phone_number
            }

            $existingUser->update($updateData);
            $user = $existingUser;
        } else {
            // Create new user
            $userData = [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'country' => $request->country,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ];

            // Only add phone_number if provided and unique
            if ($request->filled('phone_number')) {
                $phoneExists = User::where('phone_number', $request->phone_number)->exists();
                if (!$phoneExists) {
                    $userData['phone_number'] = $request->phone_number;
                }
                // If phone exists, just don't set it (allow null)
            }

            $user = User::create($userData);
        }

        Auth::guard('web')->login($user);

        $message = $existingUser 
            ? 'Your account has been activated successfully! Your payment information has been linked to this account.'
            : 'Account created successfully!';

        return redirect()->route('customer.dashboard')
            ->with('success', $message);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'You have been logged out successfully.');
    }
}
