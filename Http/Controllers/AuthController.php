<?php

namespace Offlineagency\LaravelWebex\Http\Controllers;

use Illuminate\Http\Response;
use Offlineagency\LaravelWebex\Events\SuccessfulAuthentication;
use Offlineagency\LaravelWebex\Events\WebexClientConfigured;

class AuthController extends Controller
{
    public function auth(): Response
    {
        event(new WebexClientConfigured);
        event(new SuccessfulAuthentication);

        return response('OK', 200);
    }
}
