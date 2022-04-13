<?php

use Illuminate\Support\Facades\Route;

Route::get('/auth', 'AuthController@auth')->name('auth');
