<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Devices\Device as DeviceEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Devices', function () {
    it('lists devices', function () {
        Http::fake([
            'https://webexapis.com/v1/devices*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'dev1',
                        'displayName' => 'Room Device',
                        'deviceName' => 'device1',
                        'model' => 'RoomKit',
                        'serial' => 'SN123',
                        'organizationId' => 'org1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->devices()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(DeviceEntity::class);
        expect($list[0]->id)->toEqual('dev1');
    });

    it('gets device detail', function () {
        Http::fake([
            'https://webexapis.com/v1/devices/dev1*' => Http::response(json_encode((object) [
                'id' => 'dev1',
                'displayName' => 'Room Device',
                'deviceName' => 'device1',
                'model' => 'RoomKit',
                'serial' => 'SN123',
                'organizationId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $device = $laravel_webex->devices()->detail('dev1');

        expect($device)->toBeInstanceOf(DeviceEntity::class);
        expect($device->id)->toEqual('dev1');
    });

    it('destroys device', function () {
        Http::fake([
            'https://webexapis.com/v1/devices/dev1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->devices()->destroy('dev1');

        expect($result)->toBeTrue();
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/devices*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->devices()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
