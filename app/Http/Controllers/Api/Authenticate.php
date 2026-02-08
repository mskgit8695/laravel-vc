<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PDOException;

class Authenticate extends Controller
{
    public function register(Request $request)
    {
        try {
            $user = User::create($request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => 'required|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'employee_id' => 'required|string|max:50|unique:users',
            ], [
                'first_name.required' => 'The first name is required!',
                'first_name.string' => 'The first name must be a string!',
                'first_name.max' => 'The first name should not exceed 100 characters in length!',
                'last_name.required' => 'The last name is required!',
                'last_name.string' => 'The last name must be a string!',
                'last_name.max' => 'The last name should not exceed 100 characters in length!',
                'email.required' => 'The email is required!',
                'email.regex' => 'The email is not valid!',
                'email.max' => 'The email should not exceed 250 characters in length!',
                'email.unique' => 'The email is already registered with us!',
                'password.required' => 'The password is required!',
                'password.min' => 'The password must be at least 8 characters long!',
                'password.confirmed' => 'The confirmed password does not match the entered password!',
                'employee_id.required' => 'The employee id is required!',
                'employee_id.unique' => 'The employee id is already on file with us!',
                'employee_id.max' => 'The employee id should not exceed 50 characters in length!',
            ]));

            return response()->json(['message' => 'Successfully registered', 'user' => $user], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 400);
        } catch (PDOException $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 400);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 400);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $feilds = $request->validate([
                'email' => 'required|string|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|exists:users',
                'password' => 'required|string|min:8',
                'remember' => 'sometimes|boolean',
            ], [
                'email.required' => 'The email is required!',
                'email.regex' => 'The email is not valid!',
                'email.exists' => 'The email is not registered with us!',
                'password.required' => 'The password is required!',
                'password.min' => 'The password must be at least 8 characters long!',
            ]);

            $user = User::where('email', $feilds['email'])->first();

            if (!$user || !Hash::check($feilds['password'], $user->password)) {
                return response()->json(['message' => 'The email or password you entered is incorrect. Please verify your credentials and try again.'], 400);
            }

            // Api Access Token can be created here if needed
            $token = $user->createToken($user->email)->plainTextToken;

            return response()->json(['message' => 'Successfully logged in', 'user' => $user, 'token' => $token], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 400);
        } catch (PDOException $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 400);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 400);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
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
