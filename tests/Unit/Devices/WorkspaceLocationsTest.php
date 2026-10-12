<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Devices\WorkspaceLocation as WorkspaceLocationEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Devices WorkspaceLocations', function () {
    it('lists workspace locations', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'dwl1',
                        'name' => 'Device Workspace Location One',
                        'orgId' => 'org1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->device_workspace_locations()->list();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(WorkspaceLocationEntity::class);
        expect($list[0]->id)->toEqual('dwl1');
    });

    it('gets workspace location detail', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations/dwl1*' => Http::response(json_encode((object) [
                'id' => 'dwl1',
                'name' => 'Device Workspace Location One',
                'orgId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $location = $laravel_webex->device_workspace_locations()->detail('dwl1');
        assert($location instanceof WorkspaceLocationEntity);

        expect($location)->toBeInstanceOf(WorkspaceLocationEntity::class);
        expect($location->id)->toEqual('dwl1');
    });

    it('creates workspace location', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations' => Http::response(json_encode((object) [
                'id' => 'dwl2',
                'name' => 'New Device Workspace Location',
                'orgId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $location = $laravel_webex->device_workspace_locations()->create(['name' => 'New Device Workspace Location', 'orgId' => 'org1']);
        assert($location instanceof WorkspaceLocationEntity);

        expect($location)->toBeInstanceOf(WorkspaceLocationEntity::class);
        expect($location->id)->toEqual('dwl2');
    });

    it('destroys workspace location', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations/dwl1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_workspace_locations()->destroy('dwl1');

        expect($result)->toBeTrue();
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_workspace_locations()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('updates workspace location', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations/dwl1*' => Http::response(json_encode((object) [
                'id' => 'dwl1',
                'displayName' => 'Updated',
                'organizationId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $location = $laravel_webex->device_workspace_locations()->update('dwl1', ['displayName' => 'Updated']);
        assert($location instanceof WorkspaceLocationEntity);

        expect($location)->toBeInstanceOf(WorkspaceLocationEntity::class);
        expect($location->id)->toEqual('dwl1');
        expect($location->displayName)->toEqual('Updated');

        Http::assertSent(fn (Request $request) => $request->method() === 'PUT'
            && $request->url() === 'https://webexapis.com/v1/workspaceLocations/dwl1'
            && $request['displayName'] === 'Updated');
    });

    it('returns error on detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations/dwl1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_workspace_locations()->detail('dwl1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });

    it('returns error on create failure', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_workspace_locations()->create(['displayName' => 'New Device Workspace Location']);
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Bad Request');
    });

    it('returns error on update failure', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations/dwl1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_workspace_locations()->update('dwl1', ['displayName' => 'Updated']);
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });

    it('returns error on destroy failure', function () {
        Http::fake([
            'https://webexapis.com/v1/workspaceLocations/dwl1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->device_workspace_locations()->destroy('dwl1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });
});
