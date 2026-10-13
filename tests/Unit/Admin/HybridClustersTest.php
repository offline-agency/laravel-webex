<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\HybridCluster as HybridClusterEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin HybridClusters', function () {
    it('lists hybrid clusters', function () {
        Http::fake([
            'https://webexapis.com/v1/hybrid/clusters*' => Http::response(json_encode((object) [
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
            'https://webexapis.com/v1/hybrid/clusters/hc1*' => Http::response(json_encode((object) [
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

});
