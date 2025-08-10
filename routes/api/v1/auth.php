<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

use App\Http\Controllers\Auth\Api\V1\LoginApiController;
use App\Http\Controllers\Auth\Api\V1\LogoutApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')
    ->as('auth.')
    ->group(function () {
        Route::post('/login', LoginApiController::class)->name('login');

        Route::middleware(['auth:api'])
            ->group(function () {
                Route::post('/logout', LogoutApiController::class)->name('logout');
            });
    });
