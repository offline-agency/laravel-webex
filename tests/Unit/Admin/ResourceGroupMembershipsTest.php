<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\ResourceGroupMembership as ResourceGroupMembershipEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin ResourceGroupMemberships', function () {
    it('lists resource group memberships', function () {
        Http::fake([
            'https://webexapis.com/v1/resourceGroupMemberships*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'rgm1',
                        'resourceGroupId' => 'rg1',
                        'personId' => 'p1',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_resource_group_memberships()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(ResourceGroupMembershipEntity::class);
        expect($list[0]->id)->toEqual('rgm1');
    });

    it('gets resource group membership detail', function () {
        Http::fake([
            'https://webexapis.com/v1/resourceGroupMemberships/rgm1*' => Http::response(json_encode((object) [
                'id' => 'rgm1',
                'resourceGroupId' => 'rg1',
                'personId' => 'p1',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $membership = $laravel_webex->admin_resource_group_memberships()->detail('rgm1');

        expect($membership)->toBeInstanceOf(ResourceGroupMembershipEntity::class);
        expect($membership->id)->toEqual('rgm1');
    });

    it('creates resource group membership', function () {
        Http::fake([
            'https://webexapis.com/v1/resourceGroupMemberships' => Http::response(json_encode((object) [
                'id' => 'rgm2',
                'resourceGroupId' => 'rg1',
                'personId' => 'p2',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $membership = $laravel_webex->admin_resource_group_memberships()->create(['resourceGroupId' => 'rg1', 'personId' => 'p2']);

        expect($membership)->toBeInstanceOf(ResourceGroupMembershipEntity::class);
        expect($membership->id)->toEqual('rgm2');
    });

    it('destroys resource group membership', function () {
        Http::fake([
            'https://webexapis.com/v1/resourceGroupMemberships/rgm1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_resource_group_memberships()->destroy('rgm1');

        expect($result)->toBeTrue();
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/resourceGroupMemberships*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_resource_group_memberships()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
