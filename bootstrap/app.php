<?php

use App\Http\Middleware\Authenticate;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders()
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(AppServiceProvider::HOME);

        $middleware->web(\App\Http\Middleware\HandleInertiaRequests::class);

        $middleware->throttleApi();

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'auth' => Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);

        $middleware->replace(\Illuminate\Http\Middleware\TrustProxies::class, \App\Http\Middleware\TrustProxies::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->wantsJson()) {
                $transformed = [];
                foreach ($e->validator->getMessageBag()->toArray() as $field => $message) {
                    $transformed[] = [
                        'field' => $field,
                        'message' => $message[0]
                    ];
                }

                return response()->json([
                    'message' => trans('The given data was invalid.'),
                    'errors' => $transformed,
                ], 422);
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => app()->isProduction() ? 'Something went wrong.' : $e->getMessage(),
                ], 500);
            }

            return $request;
        });
    })->create();

