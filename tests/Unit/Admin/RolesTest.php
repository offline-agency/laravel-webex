<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Admin\Role as RoleEntity;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Admin Roles', function () {
    it('lists roles', function () {
        Http::fake([
            'https://webexapis.com/v1/roles*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'role1',
                        'name' => 'Admin',
                        'description' => 'Full administrator',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->admin_roles()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(RoleEntity::class);
        expect($list[0]->id)->toEqual('role1');
    });

    it('gets role detail', function () {
        Http::fake([
            'https://webexapis.com/v1/roles/role1*' => Http::response(json_encode((object) [
                'id' => 'role1',
                'name' => 'Admin',
                'description' => 'Full administrator',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $role = $laravel_webex->admin_roles()->detail('role1');

        expect($role)->toBeInstanceOf(RoleEntity::class);
        expect($role->id)->toEqual('role1');
    });

    it('returns error on role detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/roles/role1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ]), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_roles()->detail('role1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/roles*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->admin_roles()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });
});
