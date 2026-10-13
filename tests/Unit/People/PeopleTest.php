<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\People as PeopleEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('People', function () {
    it('lists people', function () {
        Http::fake([
            'https://webexapis.com/v1/people*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'p1', 'emails' => ['u@example.com'], 'displayName' => 'User'],
                ],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->people()->listPeople();
        assert(is_array($list));

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(PeopleEntity::class);
        expect($list[0]->id)->toEqual('p1');
    });

    it('creates person', function () {
        Http::fake([
            'https://webexapis.com/v1/people*' => Http::response(json_encode((object) [
                'id' => 'p2',
                'emails' => ['new@example.com'],
                'displayName' => 'New User',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $person = $laravel_webex->people()->createPerson('new@example.com');
        assert($person instanceof PeopleEntity);

        expect($person)->toBeInstanceOf(PeopleEntity::class);
        expect($person->id)->toEqual('p2');
    });

    it('gets person detail', function () {
        Http::fake([
            'https://webexapis.com/v1/people/p1*' => Http::response(json_encode((object) [
                'id' => 'p1',
                'emails' => ['u@example.com'],
                'displayName' => 'User',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $person = $laravel_webex->people()->detailPerson('p1');
        assert($person instanceof PeopleEntity);

        expect($person)->toBeInstanceOf(PeopleEntity::class);
        expect($person->id)->toEqual('p1');
    });

    it('updates person', function () {
        Http::fake([
            'https://webexapis.com/v1/people/p1*' => Http::response(json_encode((object) [
                'id' => 'p1',
                'emails' => ['u@example.com'],
                'displayName' => 'Updated Name',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $person = $laravel_webex->people()->updatePerson('p1', 'Updated Name');
        assert($person instanceof PeopleEntity);

        expect($person)->toBeInstanceOf(PeopleEntity::class);
        expect($person->displayName)->toEqual('Updated Name');
    });

    it('destroys person', function () {
        Http::fake([
            'https://webexapis.com/v1/people/p1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->people()->destroyPerson('p1');

        expect($result)->toEqual('Person deleted');
    });

    it('gets current user (detail own)', function () {
        Http::fake([
            'https://webexapis.com/v1/people*' => Http::response(json_encode((object) [
                'id' => 'me1',
                'emails' => ['me@example.com'],
                'displayName' => 'Current User',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $person = $laravel_webex->people()->detailOwn();
        assert($person instanceof PeopleEntity);

        expect($person)->toBeInstanceOf(PeopleEntity::class);
        expect($person->id)->toEqual('me1');
        expect($person->displayName)->toEqual('Current User');
    });

    it('returns error on person detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/people/p1*' => Http::response(json_encode((object) [
                'message' => 'fake_message',
                'errors' => [],
                'trackingId' => 'fake_trackingId',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->people()->detailPerson('p1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('fake_message');
        expect($result->errors)->toBeArray();
        expect($result->trackingId)->toEqual('fake_trackingId');
    });
});
