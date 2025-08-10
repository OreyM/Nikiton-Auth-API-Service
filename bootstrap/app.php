<?php

use App\Http\Middleware\ApiForceJsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            ApiForceJsonResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'success'   => false,
                'code'      => Response::HTTP_UNAUTHORIZED,
                'message'   => trans('auth.unauthenticated')
            ], Response::HTTP_UNAUTHORIZED);
        });

        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            return response()->json([
                'success'   => false,
                'code'      => Response::HTTP_TOO_MANY_REQUESTS,
                'message'   => trans('auth.throttle', ['seconds' => 60])
            ], Response::HTTP_TOO_MANY_REQUESTS);
        });
    })
    ->create();
