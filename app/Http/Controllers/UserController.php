<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('links.index');
        }
        return view('auth.login');
    }

    //Code for user registration
    public function register(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:8|confirmed",
        ]);

        // Hash the password for security
        $validated['password'] = Hash::make($validated['password']);

        // Create new user in database
        $user = User::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'User registered successfully!'], 201);
        }

        Auth::login($user);

        return redirect()->route('links.index')->with('success', 'User registered successfully!');
    }
    
    //Code for user login
    public function login(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            "email" => "required|email",
            "password" => "required|string|min:8",
        ]);

        // Try to authenticate
        if (Auth::attempt($validated)) {
            // Regenerate session for security
            $request->session()->regenerate();

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Logged in successfully']);
            }

            return redirect()->intended(route('links.index'))->with('status', 'Welcome back, ' . Auth::user()->name . '!');
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        return redirect()->back()->withErrors(['email' => 'Invalid email or password'])->withInput();
    }

    // Show profile edit form
    public function editProfile()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    // Update profile data (including optional password update)
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'current_password' => 'required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            if (!Hash::check($validated['current_password'] ?? '', $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
            }

            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('links.index')->with('status', 'Profile updated successfully.');
    }

    //Code for user logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
