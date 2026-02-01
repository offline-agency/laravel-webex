<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\License as LicenseEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin Licenses', function () {
    it('lists licenses', function () {
        Http::fake([
            'https://webexapis.com/v1/licenses*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'lic1',
                        'name' => 'Meeting',
                        'totalUnits' => 100,
                        'consumedUnits' => 50,
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_licenses()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(LicenseEntity::class);
        expect($list[0]->id)->toEqual('lic1');
    });

    it('gets license detail', function () {
        Http::fake([
            'https://webexapis.com/v1/licenses/lic1*' => Http::response(json_encode((object) [
                'id' => 'lic1',
                'name' => 'Meeting',
                'totalUnits' => 100,
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $license = $laravel_webex->admin_licenses()->detail('lic1');

        expect($license)->toBeInstanceOf(LicenseEntity::class);
        expect($license->id)->toEqual('lic1');
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/licenses*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_licenses()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
