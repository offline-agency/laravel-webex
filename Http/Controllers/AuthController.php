<?php

namespace Offlineagency\LaravelWebex\Http\Controllers;

class AuthController extends Controller
{
    public function auth(): string
    {
        return response(
            'OK',
            200
        );
    }
}
