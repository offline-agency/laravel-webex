<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\HybridConnector as HybridConnectorEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin HybridConnectors', function () {
    it('lists hybrid connectors', function () {
        Http::fake([
            'https://webexapis.com/v1/hybrid/connectors*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'hconn1',
                        'name' => 'Connector One',
                        'clusterId' => 'hc1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_hybrid_connectors()->list();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(HybridConnectorEntity::class);
        expect($list[0]->id)->toEqual('hconn1');
    });

    it('gets hybrid connector detail', function () {
        Http::fake([
            'https://webexapis.com/v1/hybrid/connectors/hconn1*' => Http::response(json_encode((object) [
                'id' => 'hconn1',
                'name' => 'Connector One',
                'clusterId' => 'hc1',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $connector = $laravel_webex->admin_hybrid_connectors()->detail('hconn1');
        assert($connector instanceof HybridConnectorEntity);

        expect($connector)->toBeInstanceOf(HybridConnectorEntity::class);
        expect($connector->id)->toEqual('hconn1');
    });

});
