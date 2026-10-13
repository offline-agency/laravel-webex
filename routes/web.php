<?php

use Illuminate\Support\Facades\Route;
use Offlineagency\LaravelWebex\Http\Controllers\AuthController;

$route = config('webex.auth_route');
$path = is_array($route) && is_string($route['path'] ?? null) ? $route['path'] : '/auth';
$name = is_array($route) && is_string($route['name'] ?? null) ? $route['name'] : 'auth';
$middleware = config('webex.auth_route_middleware', ['throttle:60,1']);

Route::get($path, [AuthController::class, 'auth'])
    ->middleware(is_string($middleware) ? $middleware : (is_array($middleware) ? array_values(array_filter($middleware, is_string(...))) : []))
    ->name($name);
