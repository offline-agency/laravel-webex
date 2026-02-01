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
];
