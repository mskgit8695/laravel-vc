<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Handle login logic here
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if (Auth::attempt(['email' => Str::lower($request->input('email')), 'password' => $request->input('password'), 'status' => 1])) {
            return redirect('/dashboard');
        } else {
            return redirect()->back()->with(['error' => 'Either your email or password is incorrect, or your account is not active.']);
        }
    }

    public function register(Request $request)
    {
        // Handle registration logic here
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'employee_id' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Split fullname into first_name and last_name
        $name_parts = explode(' ', $request->input('fullname'), 2);
        $first_name = trim($name_parts[0]) ?? '';
        $last_name = trim($name_parts[1]) ?? '';

        // Create the user
        User::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => Str::lower($request->input('email')),
            'employee_id' => $request->input('employee_id'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Redirect to login page with success message
        return redirect('/login')->with('success', 'A confirmation email has been sent to your email address. Please verify your email before logging in.');
    }

    public function logout(Request $request)
    {
        // Handle logout logic here
        Auth::logout();

        // Invalidate the session and regenerate the CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
