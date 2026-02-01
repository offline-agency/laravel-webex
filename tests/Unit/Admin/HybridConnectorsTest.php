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

    it('updates hybrid connector', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridConnectors/hconn1*' => Http::response(json_encode((object) [
                'id' => 'hconn1',
                'name' => 'Updated Connector',
                'clusterId' => 'hc1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $connector = $laravel_webex->admin_hybrid_connectors()->update('hconn1', ['name' => 'Updated']);

        expect($connector)->toBeInstanceOf(HybridConnectorEntity::class);
        expect($connector->name)->toEqual('Updated Connector');
    });

    it('destroys hybrid connector', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridConnectors/hconn1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_hybrid_connectors()->destroy('hconn1');

        expect($result)->toBeTrue();
    });

    it('returns error on hybrid connector detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridConnectors/hconn1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ]), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_hybrid_connectors()->detail('hconn1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on create hybrid connector failure', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridConnectors' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ]), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_hybrid_connectors()->create(['name' => 'New', 'clusterId' => 'hc1']);

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on update hybrid connector failure', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridConnectors/hconn1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_hybrid_connectors()->update('hconn1', ['name' => 'Updated']);

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on destroy hybrid connector failure', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridConnectors/hconn1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_hybrid_connectors()->destroy('hconn1');

        expect($result)->toBeInstanceOf(Error::class);
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
