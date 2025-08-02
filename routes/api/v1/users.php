<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/users')
    ->as('users.')
    ->group(function () {
        Route::prefix('/user')
            ->as('user.')
            ->group(function () {
                Route::middleware('auth:api')
                    ->group(function () {
                        Route::get('/', function (Request $request) {
                            return $request->user();
                        });
                    });
            });
    });
