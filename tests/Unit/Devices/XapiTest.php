<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Devices Xapi', function () {
    it('sends xapi command and returns response data', function () {
        $responseData = (object) [
            'result' => 'ok',
            'status' => 'success',
        ];
        Http::fake([
            'https://webexapis.com/v1/xapi/commands/dev1*' => Http::response(json_encode($responseData)),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->xapi()->command('dev1', 'Dial', ['Number' => '1234']);

        expect($result)->toEqual($responseData);
    });

    it('gets xapi status and returns response data', function () {
        Http::fake([
            'https://webexapis.com/v1/xapi/status/dev1*' => Http::response(json_encode((object) [
                'result' => (object) ['status' => 'active'],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->xapi()->status('dev1', 'Audio.Volume');

        expect($result)->toBeObject();
        expect($result->result->status)->toEqual('active');
    });

    it('returns error on command failure', function () {
        Http::fake([
            'https://webexapis.com/v1/xapi/commands/dev1*' => Http::response(json_encode((object) [
                'message' => 'Device not found',
                'errors' => [],
                'trackingId' => 't1',
            ]), 404),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->xapi()->command('dev1', 'Dial', []);

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on status failure', function () {
        Http::fake([
            'https://webexapis.com/v1/xapi/status/dev1*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->xapi()->status('dev1', 'Audio.Volume');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
