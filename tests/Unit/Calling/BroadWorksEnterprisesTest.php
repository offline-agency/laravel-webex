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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->broadworks_enterprises()->list();
        assert(is_array($list));

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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $enterprise = $laravel_webex->broadworks_enterprises()->detail('ent1');
        assert($enterprise instanceof BroadWorksEnterpriseEntity);

        expect($enterprise)->toBeInstanceOf(BroadWorksEnterpriseEntity::class);
        expect($enterprise->id)->toEqual('ent1');
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/broadworksEnterprises*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->broadworks_enterprises()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/broadworksEnterprises/ent1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->broadworks_enterprises()->detail('ent1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });
});
