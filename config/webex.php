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
    | Gran type for request body
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
    | Gran type for request body
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
        'id' => '',
        'secret' => '',
        'code' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Redirect uri
    |--------------------------------------------------------------------------
    |
    | Redirect url
    |
    */
    'redirect_uri' => '',
];
