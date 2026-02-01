<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Devices\DeviceConfiguration as DeviceConfigurationEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Device Configurations', function () {
    it('lists device configurations', function () {
        Http::fake([
            'https://webexapis.com/v1/deviceConfigurations*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'cfg1',
                        'deviceId' => 'dev1',
                        'configKey' => 'key1',
                        'value' => 'value1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->device_configurations()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(DeviceConfigurationEntity::class);
        expect($list[0]->id)->toEqual('cfg1');
    });

    it('updates device configurations', function () {
        Http::fake([
            'https://webexapis.com/v1/deviceConfigurations*' => Http::response(json_encode((object) [
                'deviceId' => 'dev1',
                'configurations' => [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->device_configurations()->update([
            'deviceId' => 'dev1',
            'configurations' => [],
        ]);

        expect($result)->toBeObject();
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/deviceConfigurations*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->device_configurations()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
