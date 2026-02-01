<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\Location as LocationEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin Locations', function () {
    it('lists locations', function () {
        Http::fake([
            'https://webexapis.com/v1/locations*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'loc1',
                        'name' => 'Location One',
                        'orgId' => 'org1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_locations()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(LocationEntity::class);
        expect($list[0]->id)->toEqual('loc1');
    });

    it('gets location detail', function () {
        Http::fake([
            'https://webexapis.com/v1/locations/loc1*' => Http::response(json_encode((object) [
                'id' => 'loc1',
                'name' => 'Location One',
                'orgId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $location = $laravel_webex->admin_locations()->detail('loc1');

        expect($location)->toBeInstanceOf(LocationEntity::class);
        expect($location->id)->toEqual('loc1');
    });

    it('creates location', function () {
        Http::fake([
            'https://webexapis.com/v1/locations' => Http::response(json_encode((object) [
                'id' => 'loc2',
                'name' => 'New Location',
                'orgId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $location = $laravel_webex->admin_locations()->create(['name' => 'New Location', 'orgId' => 'org1']);

        expect($location)->toBeInstanceOf(LocationEntity::class);
        expect($location->id)->toEqual('loc2');
    });

    it('updates location', function () {
        Http::fake([
            'https://webexapis.com/v1/locations/loc1*' => Http::response(json_encode((object) [
                'id' => 'loc1',
                'name' => 'Updated Location',
                'orgId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $location = $laravel_webex->admin_locations()->update('loc1', ['name' => 'Updated Location']);

        expect($location)->toBeInstanceOf(LocationEntity::class);
        expect($location->name)->toEqual('Updated Location');
    });

    it('destroys location', function () {
        Http::fake([
            'https://webexapis.com/v1/locations/loc1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_locations()->destroy('loc1');

        expect($result)->toBeTrue();
    });

    it('returns error on location detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/locations/loc1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ]), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_locations()->detail('loc1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on create location failure', function () {
        Http::fake([
            'https://webexapis.com/v1/locations' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ]), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_locations()->create(['name' => 'New Location', 'orgId' => 'org1']);

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on update location failure', function () {
        Http::fake([
            'https://webexapis.com/v1/locations/loc1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_locations()->update('loc1', ['name' => 'Updated Location']);

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on destroy location failure', function () {
        Http::fake([
            'https://webexapis.com/v1/locations/loc1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_locations()->destroy('loc1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/locations*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_locations()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
