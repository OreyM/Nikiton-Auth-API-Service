<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

use App\Http\Controllers\Auth\Api\LoginApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')
    ->as('auth.')
    ->group(function () {
        Route::post('/login', LoginApiController::class)->name('login');
    });
