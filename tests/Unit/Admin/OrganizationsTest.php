<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\Organization as OrganizationEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin Organizations', function () {
    it('lists organizations', function () {
        Http::fake([
            'https://webexapis.com/v1/organizations*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'org1',
                        'displayName' => 'Org One',
                        'name' => 'org-one',
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_organizations()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(OrganizationEntity::class);
        expect($list[0]->id)->toEqual('org1');
    });

    it('gets organization detail', function () {
        Http::fake([
            'https://webexapis.com/v1/organizations/org1*' => Http::response(json_encode((object) [
                'id' => 'org1',
                'displayName' => 'Org One',
                'name' => 'org-one',
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $org = $laravel_webex->admin_organizations()->detail('org1');

        expect($org)->toBeInstanceOf(OrganizationEntity::class);
        expect($org->id)->toEqual('org1');
    });

    it('destroys organization', function () {
        Http::fake([
            'https://webexapis.com/v1/organizations/org1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_organizations()->destroy('org1');

        expect($result)->toBeTrue();
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/organizations*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_organizations()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
