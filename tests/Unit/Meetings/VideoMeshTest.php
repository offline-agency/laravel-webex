<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\VideoMesh as VideoMeshEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('VideoMesh', function () {
    it('lists cluster availability', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clusters/availability*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'c1', 'orgId' => 'o1'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listClusterAvailability('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'o1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('returns error on list cluster availability failure', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clusters/availability*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->listClusterAvailability('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'o1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('gets cluster availability detail', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clusters/availability/c1*' => Http::response(json_encode((object) [
                'id' => 'c1',
                'availability' => [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailClusterAvailability('c1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('lists node availability', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/nodes/availability*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'n1', 'clusterId' => 'c1'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listNodeAvailability('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'c1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets node availability detail', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/nodes/availability/n1*' => Http::response(json_encode((object) [
                'id' => 'n1',
                'clusterId' => 'c1',
                'availability' => [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailNodeAvailability('n1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('returns error on list node availability failure', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/nodes/availability*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->listNodeAvailability('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'c1');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
