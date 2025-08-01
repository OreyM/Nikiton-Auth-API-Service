<?php

use App\Http\Controllers\Auth\Api\LoginApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', LoginApiController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
