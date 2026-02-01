<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Calling\BroadWorksEnterprise as BroadWorksEnterpriseEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Calling BroadWorksEnterprises', function () {
    it('lists broadworks enterprises', function () {
        Http::fake([
            'https://webexapis.com/v1/broadworksEnterprises*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'ent1',
                        'name' => 'Enterprise One',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->broadworks_enterprises()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(BroadWorksEnterpriseEntity::class);
        expect($list[0]->id)->toEqual('ent1');
    });

    it('gets broadworks enterprise detail', function () {
        Http::fake([
            'https://webexapis.com/v1/broadworksEnterprises/ent1*' => Http::response(json_encode((object) [
                'id' => 'ent1',
                'name' => 'Enterprise One',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $enterprise = $laravel_webex->broadworks_enterprises()->detail('ent1');

        expect($enterprise)->toBeInstanceOf(BroadWorksEnterpriseEntity::class);
        expect($enterprise->id)->toEqual('ent1');
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/broadworksEnterprises*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->broadworks_enterprises()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
