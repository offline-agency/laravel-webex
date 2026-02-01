<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\Workspace as WorkspaceEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin Workspaces', function () {
    it('lists workspaces', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'ws1',
                        'name' => 'Workspace One',
                        'orgId' => 'org1',
                        'locationId' => 'loc1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_workspaces()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(WorkspaceEntity::class);
        expect($list[0]->id)->toEqual('ws1');
    });

    it('gets workspace detail', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces/ws1*' => Http::response(json_encode((object) [
                'id' => 'ws1',
                'name' => 'Workspace One',
                'orgId' => 'org1',
                'locationId' => 'loc1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $workspace = $laravel_webex->admin_workspaces()->detail('ws1');

        expect($workspace)->toBeInstanceOf(WorkspaceEntity::class);
        expect($workspace->id)->toEqual('ws1');
    });

    it('creates workspace', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces' => Http::response(json_encode((object) [
                'id' => 'ws2',
                'name' => 'New Workspace',
                'orgId' => 'org1',
                'locationId' => 'loc1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $workspace = $laravel_webex->admin_workspaces()->create(['name' => 'New Workspace', 'orgId' => 'org1', 'locationId' => 'loc1']);

        expect($workspace)->toBeInstanceOf(WorkspaceEntity::class);
        expect($workspace->id)->toEqual('ws2');
    });

    it('updates workspace', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces/ws1*' => Http::response(json_encode((object) [
                'id' => 'ws1',
                'name' => 'Updated Workspace',
                'orgId' => 'org1',
                'locationId' => 'loc1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $workspace = $laravel_webex->admin_workspaces()->update('ws1', ['name' => 'Updated Workspace']);

        expect($workspace)->toBeInstanceOf(WorkspaceEntity::class);
        expect($workspace->name)->toEqual('Updated Workspace');
    });

    it('destroys workspace', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces/ws1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_workspaces()->destroy('ws1');

        expect($result)->toBeTrue();
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_workspaces()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
