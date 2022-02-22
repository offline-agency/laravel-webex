<?php

namespace Offlineagency\LaravelWebex\Http\Controllers;

use Offlineagency\LaravelWebex\Events\SuccessfulAuthentication;

class AuthController extends Controller
{
    public function auth(): string
    {
        event(new SuccessfulAuthentication());

        return response(
            'OK',
            200
        );
    }
}
