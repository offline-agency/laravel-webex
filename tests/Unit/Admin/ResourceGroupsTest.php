<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\ResourceGroup as ResourceGroupEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin ResourceGroups', function () {
    it('lists resource groups', function () {
        Http::fake([
            'https://webexapis.com/v1/resourceGroups*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'rg1',
                        'name' => 'Resource Group One',
                        'orgId' => 'org1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->admin_resource_groups()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(ResourceGroupEntity::class);
        expect($list[0]->id)->toEqual('rg1');
    });

    it('gets resource group detail', function () {
        Http::fake([
            'https://webexapis.com/v1/resourceGroups/rg1*' => Http::response(json_encode((object) [
                'id' => 'rg1',
                'name' => 'Resource Group One',
                'orgId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $group = $laravel_webex->admin_resource_groups()->detail('rg1');

        expect($group)->toBeInstanceOf(ResourceGroupEntity::class);
        expect($group->id)->toEqual('rg1');
    });

    it('creates resource group', function () {
        Http::fake([
            'https://webexapis.com/v1/resourceGroups' => Http::response(json_encode((object) [
                'id' => 'rg2',
                'name' => 'New Resource Group',
                'orgId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $group = $laravel_webex->admin_resource_groups()->create(['name' => 'New Resource Group', 'orgId' => 'org1']);

        expect($group)->toBeInstanceOf(ResourceGroupEntity::class);
        expect($group->id)->toEqual('rg2');
    });

    it('updates resource group', function () {
        Http::fake([
            'https://webexapis.com/v1/resourceGroups/rg1*' => Http::response(json_encode((object) [
                'id' => 'rg1',
                'name' => 'Updated Resource Group',
                'orgId' => 'org1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $group = $laravel_webex->admin_resource_groups()->update('rg1', ['name' => 'Updated Resource Group']);

        expect($group)->toBeInstanceOf(ResourceGroupEntity::class);
        expect($group->name)->toEqual('Updated Resource Group');
    });

    it('destroys resource group', function () {
        Http::fake([
            'https://webexapis.com/v1/resourceGroups/rg1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->admin_resource_groups()->destroy('rg1');

        expect($result)->toBeTrue();
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/resourceGroups*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->admin_resource_groups()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
