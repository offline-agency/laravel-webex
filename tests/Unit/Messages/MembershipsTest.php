<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Membership as MembershipEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Memberships', function () {
    it('lists memberships', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'mem1', 'roomId' => 'r1', 'personEmail' => 'u@example.com', 'isModerator' => false],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->memberships()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(MembershipEntity::class);
        expect($list[0]->id)->toEqual('mem1');
    });

    it('returns error on memberships list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->memberships()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('creates membership', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships*' => Http::response(json_encode((object) [
                'id' => 'mem1',
                'roomId' => 'r1',
                'personEmail' => 'user@example.com',
                'isModerator' => false,
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $membership = $laravel_webex->memberships()->create('r1', 'user@example.com');

        expect($membership)->toBeInstanceOf(MembershipEntity::class);
        expect($membership->id)->toEqual('mem1');
    });

    it('gets membership detail', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships/mem1*' => Http::response(json_encode((object) [
                'id' => 'mem1',
                'roomId' => 'r1',
                'personEmail' => 'user@example.com',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $membership = $laravel_webex->memberships()->detail('mem1');

        expect($membership)->toBeInstanceOf(MembershipEntity::class);
        expect($membership->id)->toEqual('mem1');
    });

    it('updates membership', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships/mem1*' => Http::response(json_encode((object) [
                'id' => 'mem1',
                'roomId' => 'r1',
                'isModerator' => true,
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $membership = $laravel_webex->memberships()->update('mem1', ['isModerator' => true]);

        expect($membership)->toBeInstanceOf(MembershipEntity::class);
        expect($membership->isModerator)->toBeTrue();
    });

    it('destroys membership', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships/mem1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->memberships()->destroy('mem1');

        expect($result)->toBeTrue();
    });

    it('returns error on destroy membership failure', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships/mem1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->memberships()->destroy('mem1');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
