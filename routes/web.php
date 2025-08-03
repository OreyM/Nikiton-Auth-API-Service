<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get('/', fn () => response()->json([
    'success' => false,
    'code'    => Response::HTTP_METHOD_NOT_ALLOWED,
    'message' => Response::$statusTexts[405],
], Response::HTTP_METHOD_NOT_ALLOWED));

Route::get('/login', fn () => response()->json([
    'success' => false,
    'code'    => Response::HTTP_METHOD_NOT_ALLOWED,
    'message' => Response::$statusTexts[405],
], Response::HTTP_METHOD_NOT_ALLOWED))
    ->name('login');
