<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\ResourceGroupMembership as ResourceGroupMembershipEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin ResourceGroupMemberships', function () {
    it('lists resource group memberships', function () {
        Http::fake([
            'https://webexapis.com/v1/resourceGroup/memberships*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'rgm1',
                        'resourceGroupId' => 'rg1',
                        'personId' => 'p1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_resource_group_memberships()->list();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(ResourceGroupMembershipEntity::class);
        expect($list[0]->id)->toEqual('rgm1');
    });

    it('gets resource group membership detail', function () {
        Http::fake([
            'https://webexapis.com/v1/resourceGroup/memberships/rgm1*' => Http::response(json_encode((object) [
                'id' => 'rgm1',
                'resourceGroupId' => 'rg1',
                'personId' => 'p1',
                'created' => '2024-01-01T00:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $membership = $laravel_webex->admin_resource_group_memberships()->detail('rgm1');
        assert($membership instanceof ResourceGroupMembershipEntity);

        expect($membership)->toBeInstanceOf(ResourceGroupMembershipEntity::class);
        expect($membership->id)->toEqual('rgm1');
    });

});
