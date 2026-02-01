<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\HybridConnector as HybridConnectorEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin HybridConnectors', function () {
    it('lists hybrid connectors', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridConnectors*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'hconn1',
                        'name' => 'Connector One',
                        'clusterId' => 'hc1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_hybrid_connectors()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(HybridConnectorEntity::class);
        expect($list[0]->id)->toEqual('hconn1');
    });

    it('gets hybrid connector detail', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridConnectors/hconn1*' => Http::response(json_encode((object) [
                'id' => 'hconn1',
                'name' => 'Connector One',
                'clusterId' => 'hc1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $connector = $laravel_webex->admin_hybrid_connectors()->detail('hconn1');

        expect($connector)->toBeInstanceOf(HybridConnectorEntity::class);
        expect($connector->id)->toEqual('hconn1');
    });

    it('creates hybrid connector', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridConnectors' => Http::response(json_encode((object) [
                'id' => 'hconn2',
                'name' => 'New Connector',
                'clusterId' => 'hc1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $connector = $laravel_webex->admin_hybrid_connectors()->create(['name' => 'New Connector', 'clusterId' => 'hc1']);

        expect($connector)->toBeInstanceOf(HybridConnectorEntity::class);
        expect($connector->id)->toEqual('hconn2');
    });

    it('destroys hybrid connector', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridConnectors/hconn1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_hybrid_connectors()->destroy('hconn1');

        expect($result)->toBeTrue();
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridConnectors*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_hybrid_connectors()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
