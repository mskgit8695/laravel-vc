<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use App\Services\PasswordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(
        protected PasswordService $passwordService
    ) {}

    public function login(Request $request)
    {
        // Handle login logic here
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email:rfc,dns|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|exists:users',
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
            'email' => 'required|string|email:rfc,dns|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|max:255|unique:users',
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

    public function forgot_password(Request $request)
    {
        // Handle request here
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email:rfc,dns|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|max:255|exists:users',
        ], [
            'email.exists' => 'The email is not registered with us!'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //Send reset password link
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? redirect()->route('login')->with('success', 'Password reset link sent to your email.')
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show reset password form
     */
    public function showForm(string $token)
    {
        return view('reset-password', [
            'token' => $token,
            'email' => request('email'),
        ]);
    }

    /**
     * Handle password reset
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->update([
                    'password' => Hash::make($password),
                ]);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password reset successful. You can login now.')
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Handle change password both api and web
     */
    public function change_password(ChangePasswordRequest $request): JsonResponse|RedirectResponse
    {
        $this->passwordService->changePassword(
            $request->user(),
            $request->current_password,
            $request->password
        );

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Password changed successfully.']);
        }

        return back()->with('success', 'Password changed successfully.');
    }
}
