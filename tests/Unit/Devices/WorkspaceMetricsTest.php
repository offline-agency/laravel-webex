<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Devices\WorkspaceMetrics as WorkspaceMetricsEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Devices WorkspaceMetrics', function () {
    it('lists workspace metrics', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceMetrics*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'dwm1',
                        'orgId' => 'org1',
                        'locationId' => 'loc1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->device_workspace_metrics()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(WorkspaceMetricsEntity::class);
        expect($list[0]->id)->toEqual('dwm1');
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceMetrics*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_workspace_metrics()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
