<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->as('api.')
    ->group(function () {
        /*
         * API V1 routes
         */
        Route::prefix('/v1')
            ->as('v1.')
            ->group(function () {
                if (file_exists(__DIR__.'/api/v1/auth.php')) {
                    require __DIR__.'/api/v1/auth.php';
                }
                if (file_exists(__DIR__.'/api/v1/users.php')) {
                    require __DIR__.'/api/v1/users.php';
                }
            });
    });
