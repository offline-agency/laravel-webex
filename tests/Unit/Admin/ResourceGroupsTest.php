<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\ResourceGroup as ResourceGroupEntity;
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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_resource_groups()->list();
        assert(is_array($list));

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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $group = $laravel_webex->admin_resource_groups()->detail('rg1');
        assert($group instanceof ResourceGroupEntity);

        expect($group)->toBeInstanceOf(ResourceGroupEntity::class);
        expect($group->id)->toEqual('rg1');
    });

});
