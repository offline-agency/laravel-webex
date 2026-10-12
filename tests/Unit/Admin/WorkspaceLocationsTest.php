<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\WorkspaceLocation as WorkspaceLocationEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin WorkspaceLocations', function () {
    it('lists workspace locations', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'wl1',
                        'name' => 'Workspace Location One',
                        'orgId' => 'org1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_workspace_locations()->list();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(WorkspaceLocationEntity::class);
        expect($list[0]->id)->toEqual('wl1');
    });

    it('gets workspace location detail', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations/wl1*' => Http::response(json_encode((object) [
                'id' => 'wl1',
                'name' => 'Workspace Location One',
                'orgId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $location = $laravel_webex->admin_workspace_locations()->detail('wl1');
        assert($location instanceof WorkspaceLocationEntity);

        expect($location)->toBeInstanceOf(WorkspaceLocationEntity::class);
        expect($location->id)->toEqual('wl1');
    });

    it('creates workspace location', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations' => Http::response(json_encode((object) [
                'id' => 'wl2',
                'name' => 'New Workspace Location',
                'orgId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $location = $laravel_webex->admin_workspace_locations()->create(['name' => 'New Workspace Location', 'orgId' => 'org1']);
        assert($location instanceof WorkspaceLocationEntity);

        expect($location)->toBeInstanceOf(WorkspaceLocationEntity::class);
        expect($location->id)->toEqual('wl2');
    });

    it('updates workspace location', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations/wl1*' => Http::response(json_encode((object) [
                'id' => 'wl1',
                'name' => 'Updated Workspace Location',
                'orgId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $location = $laravel_webex->admin_workspace_locations()->update('wl1', ['name' => 'Updated Workspace Location']);
        assert($location instanceof WorkspaceLocationEntity);

        expect($location)->toBeInstanceOf(WorkspaceLocationEntity::class);
        expect($location->name)->toEqual('Updated Workspace Location');
    });

    it('destroys workspace location', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations/wl1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_workspace_locations()->destroy('wl1');

        expect($result)->toBeTrue();
    });

});
