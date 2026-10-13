<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\WorkspaceMetrics as WorkspaceMetricsEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin WorkspaceMetrics', function () {
    it('lists workspace metrics', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceMetrics*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'wm1',
                        'orgId' => 'org1',
                        'locationId' => 'loc1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_workspace_metrics()->list();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(WorkspaceMetricsEntity::class);
        expect($list[0]->id)->toEqual('wm1');
    });

});
