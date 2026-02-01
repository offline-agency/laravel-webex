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
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->people()->listPeople();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(PeopleEntity::class);
        expect($list[0]->id)->toEqual('p1');
    });

    it('returns error on people list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/people*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->people()->listPeople();

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Forbidden');
    });

    it('creates person', function () {
        Http::fake([
            'https://webexapis.com/v1/people/*' => Http::response(json_encode((object) [
                'id' => 'p2',
                'emails' => ['new@example.com'],
                'displayName' => 'New User',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $person = $laravel_webex->people()->createPerson('new@example.com');

        expect($person)->toBeInstanceOf(PeopleEntity::class);
        expect($person->id)->toEqual('p2');
    });

    it('gets person detail', function () {
        Http::fake([
            'https://webexapis.com/v1/people/p1*' => Http::response(json_encode((object) [
                'id' => 'p1',
                'emails' => ['u@example.com'],
                'displayName' => 'User',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $person = $laravel_webex->people()->detailPerson('p1');

        expect($person)->toBeInstanceOf(PeopleEntity::class);
        expect($person->id)->toEqual('p1');
    });

    it('updates person', function () {
        Http::fake([
            'https://webexapis.com/v1/people/p1*' => Http::response(json_encode((object) [
                'id' => 'p1',
                'emails' => ['u@example.com'],
                'displayName' => 'Updated Name',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $person = $laravel_webex->people()->updatePerson('p1', 'Updated Name');

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

    it('returns error on destroy person failure', function () {
        Http::fake([
            'https://webexapis.com/v1/people/p1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->people()->destroyPerson('p1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on create person failure', function () {
        Http::fake([
            'https://webexapis.com/v1/people/*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ]), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->people()->createPerson('bad@example.com');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on update person failure', function () {
        Http::fake([
            'https://webexapis.com/v1/people/p1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->people()->updatePerson('p1', 'Updated Name');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
