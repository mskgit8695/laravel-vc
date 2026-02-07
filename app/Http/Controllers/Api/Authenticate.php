<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class Authenticate extends Controller
{
    public function register(Request $request)
    {
        $user = User::create($request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]));

        return response()->json(['message' => 'Successfully registered', 'user' => $user], 200);
    }

    public function login(Request $request)
    {
        $feilds = $request->validate([
            'email' => 'required|string|email|exists:users',
            'password' => 'required|string',
            'remember' => 'sometimes|boolean',
        ]);

        $user = User::where('email', $feilds['email'])->first();

        if (!$user || !Hash::check($feilds['password'], $user->password)) {
            return response()->json(['message' => 'The provided credentials are incorrect.'], 400);
        }

        // Api Access Token can be created here if needed
        $token = $user->createToken($user->email)->plainTextToken;

        return response()->json(['message' => 'Successfully logged in', 'user' => $user, 'token' => $token], 200);
    }

    public function logout(Request $request)
    {
        // Revoke all tokens
        $request->user()->tokens()->delete();

        return response(status: 200);
    }

    public function verifyEmail(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(['message' => 'Invalid or expired verification link'], 400);
        }

        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        $user->markEmailAsVerified();

        return response()->json(['message' => 'Email successfully verified'], 200);
    }

    public function resendVerificationEmail(Request $request)
    {
        // Ensure the user is authenticated
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email resent'], 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => trans($status)])
            : response()->json(['message' => trans($status)], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => trans($status)])
            : response()->json(['message' => trans($status)], 400);
    }
}
