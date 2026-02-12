<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo('/dashboard');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Validation Exception
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // Model Not Found / 404
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Resource not found.',
                ], 404);
            }

            return response()->view('errors.404', [], 404);
        });

        // Authentication
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            return redirect()->route('login');
        });

        // Authorization Exception
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Forbidden.',
                ], 403);
            }

            return response()->view('errors.403', [], 403);
        });

        // Global Fallback Exception (Production Safe)
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => config('app.debug')
                        ? $e->getMessage()
                        : 'Something went wrong.',
                ], 500);
            }
            return response()->view('errors.500', [], 500);
        });

        // $exceptions->renderable(function (Throwable $e, Request $request) {
        //     // Handle API requests (returns JSON)
        //     if ($request->expectsJson() || $request->is('api/*')) {
        //         return match (true) {
        //             $e instanceof NotFoundHttpException => response()->json([
        //                 'message' => 'Resource not found'
        //             ], 400),
        //             $e instanceof AuthenticationException => response()->json([
        //                 'message' => 'Unauthenticated'
        //             ], 400),
        //             $e instanceof ValidationException => response()->json([
        //                 'message' => $e->getMessage(),
        //                 'errors' => $e->errors()
        //             ], 400),
        //             default => response()->json([
        //                 'message' => 'An error occurred'
        //             ], 500)
        //         };
        //     }
        //     // Handle Web requests (uses default Laravel handler to show HTML views)
        //     return parent::render($request, $e); // Use the default handler for web
        // });

        // You can also add reportable callbacks here for logging specific exceptions
        $exceptions->reportable(function (Throwable $e) {
            // Log the exception details
            Log::error('An exception occurred: ' . $e->getMessage());
        });
    })->create();
