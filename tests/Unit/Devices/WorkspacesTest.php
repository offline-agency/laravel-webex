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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->device_workspaces()->list();
        assert(is_array($list));

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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $workspace = $laravel_webex->device_workspaces()->detail('dws1');
        assert($workspace instanceof WorkspaceEntity);

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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $workspace = $laravel_webex->device_workspaces()->create(['name' => 'New Device Workspace', 'orgId' => 'org1', 'locationId' => 'loc1']);
        assert($workspace instanceof WorkspaceEntity);

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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $workspace = $laravel_webex->device_workspaces()->update('dws1', ['displayName' => 'Updated Device Workspace']);
        assert($workspace instanceof WorkspaceEntity);

        expect($workspace)->toBeInstanceOf(WorkspaceEntity::class);
        expect($workspace->displayName)->toEqual('Updated Device Workspace');
    });

    it('destroys workspace', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces/dws1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_workspaces()->destroy('dws1');

        expect($result)->toBeTrue();
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_workspaces()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces/dws1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_workspaces()->detail('dws1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });

    it('returns error on create failure', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_workspaces()->create(['displayName' => 'New Device Workspace']);
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Bad Request');
    });

    it('returns error on update failure', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces/dws1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_workspaces()->update('dws1', ['displayName' => 'Updated Device Workspace']);
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });

    it('returns error on destroy failure', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaces/dws1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_workspaces()->destroy('dws1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });
});
