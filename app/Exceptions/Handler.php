<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends Exception
{
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            return $this->handleApiException($exception);
        }

        return $this->handleWebException($exception);
    }

    protected function handleApiException(Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 400);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['message' => 'Resource not found'], 400);
        }

        return response()->json([
            'message' => config('app.debug')
                ? $exception->getMessage()
                : 'Server error',
        ], 500);
    }

    protected function handleWebException(Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            throw $exception; // Let Laravel redirect back
        }

        if ($exception instanceof AuthenticationException) {
            return redirect()->route('login');
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

        return response()->view('errors.500', [], 500);
    }
}
