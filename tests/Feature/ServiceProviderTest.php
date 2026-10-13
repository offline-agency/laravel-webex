<?php

use Offlineagency\LaravelWebex\LaravelWebex;

describe('ServiceProvider', function () {
    it('publishes webex config with default base_url', function () {
        expect(config('webex.base_url'))->toEqual('https://webexapis.com/v1/');
    });

    it('registers laravel-webex binding as LaravelWebex instance', function () {
        $instance = app('laravel-webex');

        expect($instance)->toBeInstanceOf(LaravelWebex::class);
    });
});
