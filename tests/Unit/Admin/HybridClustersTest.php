<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\HybridCluster as HybridClusterEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin HybridClusters', function () {
    it('lists hybrid clusters', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridClusters*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'hc1',
                        'name' => 'Cluster One',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_hybrid_clusters()->list();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(HybridClusterEntity::class);
        expect($list[0]->id)->toEqual('hc1');
    });

    it('gets hybrid cluster detail', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridClusters/hc1*' => Http::response(json_encode((object) [
                'id' => 'hc1',
                'name' => 'Cluster One',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $cluster = $laravel_webex->admin_hybrid_clusters()->detail('hc1');
        assert($cluster instanceof HybridClusterEntity);

        expect($cluster)->toBeInstanceOf(HybridClusterEntity::class);
        expect($cluster->id)->toEqual('hc1');
    });

    it('creates hybrid cluster', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridClusters' => Http::response(json_encode((object) [
                'id' => 'hc2',
                'name' => 'New Cluster',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $cluster = $laravel_webex->admin_hybrid_clusters()->create(['name' => 'New Cluster']);
        assert($cluster instanceof HybridClusterEntity);

        expect($cluster)->toBeInstanceOf(HybridClusterEntity::class);
        expect($cluster->id)->toEqual('hc2');
    });

    it('destroys hybrid cluster', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridClusters/hc1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_hybrid_clusters()->destroy('hc1');

        expect($result)->toBeTrue();
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridClusters*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_hybrid_clusters()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('updates hybrid cluster', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridClusters/hc1*' => Http::response(json_encode((object) [
                'id' => 'hc1',
                'name' => 'Updated',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $cluster = $laravel_webex->admin_hybrid_clusters()->update('hc1', ['name' => 'Updated']);
        assert($cluster instanceof HybridClusterEntity);

        expect($cluster)->toBeInstanceOf(HybridClusterEntity::class);
        expect($cluster->id)->toEqual('hc1');
        expect($cluster->name)->toEqual('Updated');

        Http::assertSent(fn (Request $request) => $request->method() === 'PUT'
            && $request->url() === 'https://webexapis.com/v1/hybridClusters/hc1'
            && $request['name'] === 'Updated');
    });

    it('returns error on detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridClusters/hc1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_hybrid_clusters()->detail('hc1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });

    it('returns error on create failure', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridClusters' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_hybrid_clusters()->create(['name' => 'New Cluster']);
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Bad Request');
    });

    it('returns error on update failure', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridClusters/hc1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_hybrid_clusters()->update('hc1', ['name' => 'Updated']);
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });

    it('returns error on destroy failure', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridClusters/hc1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_hybrid_clusters()->destroy('hc1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });
});
