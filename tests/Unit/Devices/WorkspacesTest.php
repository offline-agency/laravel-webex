<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Devices\Workspace as WorkspaceEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Devices Workspaces', function () {
    it('lists workspaces', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'dws1',
                        'name' => 'Device Workspace One',
                        'orgId' => 'org1',
                        'locationId' => 'loc1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->device_workspaces()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(WorkspaceEntity::class);
        expect($list[0]->id)->toEqual('dws1');
    });

    it('gets workspace detail', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces/dws1*' => Http::response(json_encode((object) [
                'id' => 'dws1',
                'name' => 'Device Workspace One',
                'orgId' => 'org1',
                'locationId' => 'loc1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $workspace = $laravel_webex->device_workspaces()->detail('dws1');

        expect($workspace)->toBeInstanceOf(WorkspaceEntity::class);
        expect($workspace->id)->toEqual('dws1');
    });

    it('creates workspace', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces' => Http::response(json_encode((object) [
                'id' => 'dws2',
                'name' => 'New Device Workspace',
                'orgId' => 'org1',
                'locationId' => 'loc1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $workspace = $laravel_webex->device_workspaces()->create(['name' => 'New Device Workspace', 'orgId' => 'org1', 'locationId' => 'loc1']);

        expect($workspace)->toBeInstanceOf(WorkspaceEntity::class);
        expect($workspace->id)->toEqual('dws2');
    });

    it('updates workspace', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces/dws1*' => Http::response(json_encode((object) [
                'id' => 'dws1',
                'displayName' => 'Updated Device Workspace',
                'locationId' => 'loc1',
                'organizationId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $workspace = $laravel_webex->device_workspaces()->update('dws1', ['displayName' => 'Updated Device Workspace']);

        expect($workspace)->toBeInstanceOf(WorkspaceEntity::class);
        expect($workspace->displayName)->toEqual('Updated Device Workspace');
    });

    it('destroys workspace', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces/dws1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->device_workspaces()->destroy('dws1');

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

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->device_workspaces()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
