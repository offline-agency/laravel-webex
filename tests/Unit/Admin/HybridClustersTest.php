<?php

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
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->admin_hybrid_clusters()->list();

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
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $cluster = $laravel_webex->admin_hybrid_clusters()->detail('hc1');

        expect($cluster)->toBeInstanceOf(HybridClusterEntity::class);
        expect($cluster->id)->toEqual('hc1');
    });

    it('creates hybrid cluster', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridClusters' => Http::response(json_encode((object) [
                'id' => 'hc2',
                'name' => 'New Cluster',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $cluster = $laravel_webex->admin_hybrid_clusters()->create(['name' => 'New Cluster']);

        expect($cluster)->toBeInstanceOf(HybridClusterEntity::class);
        expect($cluster->id)->toEqual('hc2');
    });

    it('destroys hybrid cluster', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridClusters/hc1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->admin_hybrid_clusters()->destroy('hc1');

        expect($result)->toBeTrue();
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/hybridClusters*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->admin_hybrid_clusters()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
