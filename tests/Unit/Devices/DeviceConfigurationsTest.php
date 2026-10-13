<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Devices\DeviceConfiguration as DeviceConfigurationEntity;
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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->device_configurations()->list();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(DeviceConfigurationEntity::class);
        expect($list[0]->id)->toEqual('cfg1');
    });

    it('updates device configurations', function () {
        Http::fake([
            'https://webexapis.com/v1/deviceConfigurations*' => Http::response(json_encode((object) [
                'deviceId' => 'dev1',
                'configurations' => [],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_configurations()->update([
            'deviceId' => 'dev1',
            'configurations' => [],
        ]);

        expect($result)->toBeObject();
    });

});
