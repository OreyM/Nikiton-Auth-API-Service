<?php

use App\Api\Responses\ErrorResponses\TooManyRequestsResponse;
use App\Api\Responses\ErrorResponses\UnauthorizedResponse;
use App\Api\Responses\ErrorResponses\UnprocessableEntityResponse;
use App\Domain\Auth\Exceptions\AuthFailedException;
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
            return (new UnauthorizedResponse(
                message: trans('auth.unauthenticated')
            ))->respond();
        });

        $exceptions->render(function (AuthFailedException $e, Request $request) {
            return (new UnprocessableEntityResponse(
                message: $e->getMessage()
            ))->respond();
        });

        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            // make universal
            return (new TooManyRequestsResponse(
                message: trans('auth.throttle', [
                    'seconds' => config('auth.passwords.users.throttle')
                ])
            ))->respond();
        });
    })
    ->create();
