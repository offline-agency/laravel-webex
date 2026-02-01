<?php

use Illuminate\Support\Facades\Route;
use Offlineagency\LaravelWebex\Http\Controllers\AuthController;

Route::get('/auth', [AuthController::class, 'auth'])
    ->middleware(config('webex.auth_route_middleware', ['throttle:60,1']))
    ->name('auth');
