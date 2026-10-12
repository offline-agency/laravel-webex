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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->memberships()->list();
        assert(is_array($list));

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
            ], JSON_THROW_ON_ERROR), 401),
        ]);

        $laravel_webex = new LaravelWebex;
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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $membership = $laravel_webex->memberships()->create('r1', 'user@example.com');
        assert($membership instanceof MembershipEntity);

        expect($membership)->toBeInstanceOf(MembershipEntity::class);
        expect($membership->id)->toEqual('mem1');
    });

    it('returns error on create membership failure', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->memberships()->create('r1', 'user@example.com');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('gets membership detail', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships/mem1*' => Http::response(json_encode((object) [
                'id' => 'mem1',
                'roomId' => 'r1',
                'personEmail' => 'user@example.com',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $membership = $laravel_webex->memberships()->detail('mem1');
        assert($membership instanceof MembershipEntity);

        expect($membership)->toBeInstanceOf(MembershipEntity::class);
        expect($membership->id)->toEqual('mem1');
    });

    it('returns error on membership detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships/mem1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->memberships()->detail('mem1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('updates membership', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships/mem1*' => Http::response(json_encode((object) [
                'id' => 'mem1',
                'roomId' => 'r1',
                'isModerator' => true,
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $membership = $laravel_webex->memberships()->update('mem1', ['isModerator' => true]);
        assert($membership instanceof MembershipEntity);

        expect($membership)->toBeInstanceOf(MembershipEntity::class);
        expect($membership->isModerator)->toBeTrue();
    });

    it('returns error on update membership failure', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships/mem1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->memberships()->update('mem1', ['isModerator' => true]);

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('destroys membership', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships/mem1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->memberships()->destroy('mem1');

        expect($result)->toBeTrue();
    });

    it('returns error on destroy membership failure', function () {
        Http::fake([
            'https://webexapis.com/v1/memberships/mem1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->memberships()->destroy('mem1');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
