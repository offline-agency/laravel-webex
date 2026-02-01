<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\WorkspaceLocation as WorkspaceLocationEntity;
use Offlineagency\LaravelWebex\Entities\Error;
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
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_workspace_locations()->list();

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
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $location = $laravel_webex->admin_workspace_locations()->detail('wl1');

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
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $location = $laravel_webex->admin_workspace_locations()->create(['name' => 'New Workspace Location', 'orgId' => 'org1']);

        expect($location)->toBeInstanceOf(WorkspaceLocationEntity::class);
        expect($location->id)->toEqual('wl2');
    });

    it('destroys workspace location', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations/wl1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_workspace_locations()->destroy('wl1');

        expect($result)->toBeTrue();
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_workspace_locations()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
