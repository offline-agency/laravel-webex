<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base url
    |--------------------------------------------------------------------------
    |
    | Base url for any api request.
    |
    */
    'base_url' => 'https://webexapis.com/v1/',

    /*
    |--------------------------------------------------------------------------
    | Request timeout
    |--------------------------------------------------------------------------
    |
    | Timeout in seconds for API requests. Prevents indefinite hangs.
    |
    */
    'timeout' => env('WEBEX_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Bearer
    |--------------------------------------------------------------------------
    |
    | Bearer token to authenticate requests.
    |
    */
    'bearer' => env('WEBEX_BEARER', ''),

    /*
    |--------------------------------------------------------------------------
    | Token resolver
    |--------------------------------------------------------------------------
    |
    | Optional class implementing Offlineagency\LaravelWebex\Contracts\TokenResolver:
    | asked for the bearer token before every request (refreshed OAuth tokens in
    | queue workers / Octane). Null uses 'bearer'.
    |
    */
    'token_resolver' => null,

    /*
    |--------------------------------------------------------------------------
    | Retries
    |--------------------------------------------------------------------------
    |
    | Attempts (including the first) for GET, PUT and DELETE after a network
    | failure, a 429 or a 503. The wait follows Retry-After, capped. POST and
    | PATCH are never retried. Set 'times' to 1 to disable.
    |
    */
    'retry' => [
        'times' => 3,
        'max_delay_seconds' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Access Token Request
    |--------------------------------------------------------------------------
    |
    | Access token suffix to make request.
    | Grant type for request body
    |
    */
    'access_token' => [
        'url' => 'access_token',
        'grant_type' => 'authorization_code',
    ],

    /*
    |--------------------------------------------------------------------------
    | Access Token Request
    |--------------------------------------------------------------------------
    |
    | Refresh token suffix to make request.
    | Grant type for request body
    |
    */
    'refresh_token' => [
        'url' => 'refresh_token',
        'grant_type' => 'refresh_token',
    ],

    /*
    |--------------------------------------------------------------------------
    | Client information
    |--------------------------------------------------------------------------
    |
    | Information about web integration client
    |
    */
    'client' => [
        'id' => env('WEBEX_CLIENT_ID', ''),
        'secret' => env('WEBEX_CLIENT_SECRET', ''),
        'code' => env('WEBEX_CLIENT_CODE', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Redirect uri
    |--------------------------------------------------------------------------
    |
    | Redirect url for OAuth callback.
    |
    */
    'redirect_uri' => env('WEBEX_REDIRECT_URI', ''),

    /*
    |--------------------------------------------------------------------------
    | Auth route middleware
    |--------------------------------------------------------------------------
    |
    | Middleware applied to the /auth route (e.g. throttle). Set to [] to disable.
    |
    */
    'auth_route_middleware' => ['throttle:60,1'],

    /*
    |--------------------------------------------------------------------------
    | Auth route
    |--------------------------------------------------------------------------
    |
    | The package registers GET /auth (route name "auth"). Change the path and
    | name, or set 'enabled' => false when the application has its own route
    | with that path or name.
    |
    */
    'auth_route' => [
        'enabled' => (bool) env('WEBEX_AUTH_ROUTE_ENABLED', true),
        'path' => '/auth',
        'name' => 'auth',
    ],
];
