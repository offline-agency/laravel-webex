<?php

namespace Offlineagency\LaravelWebex\Http\Controllers;

use Illuminate\Http\Response;
use Offlineagency\LaravelWebex\Events\SuccessfulAuthentication;

class AuthController extends Controller
{
    public function auth(): Response
    {
        event(new SuccessfulAuthentication);

        return response('OK', 200);
    }
}
