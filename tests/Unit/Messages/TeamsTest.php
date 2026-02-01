<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Team as TeamEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Teams', function () {
    it('lists teams', function () {
        Http::fake([
            'https://webexapis.com/v1/teams*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'team1', 'name' => 'Team One', 'creatorId' => 'u1'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->teams()->list();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(TeamEntity::class);
        expect($list[0]->id)->toEqual('team1');
        expect($list[0]->name)->toEqual('Team One');
    });

    it('returns error on teams list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/teams*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->teams()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('creates team', function () {
        Http::fake([
            'https://webexapis.com/v1/teams*' => Http::response(json_encode((object) [
                'id' => 'team1',
                'name' => 'New Team',
                'creatorId' => 'u1',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $team = $laravel_webex->teams()->create('New Team');

        expect($team)->toBeInstanceOf(TeamEntity::class);
        expect($team->id)->toEqual('team1');
        expect($team->name)->toEqual('New Team');
    });

    it('gets team detail', function () {
        Http::fake([
            'https://webexapis.com/v1/teams/team1*' => Http::response(json_encode((object) [
                'id' => 'team1',
                'name' => 'Team One',
                'creatorId' => 'u1',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $team = $laravel_webex->teams()->detail('team1');

        expect($team)->toBeInstanceOf(TeamEntity::class);
        expect($team->id)->toEqual('team1');
    });

    it('updates team', function () {
        Http::fake([
            'https://webexapis.com/v1/teams/team1*' => Http::response(json_encode((object) [
                'id' => 'team1',
                'name' => 'Updated Team',
                'creatorId' => 'u1',
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $team = $laravel_webex->teams()->update('team1', 'Updated Team');

        expect($team)->toBeInstanceOf(TeamEntity::class);
        expect($team->name)->toEqual('Updated Team');
    });

    it('destroys team', function () {
        Http::fake([
            'https://webexapis.com/v1/teams/team1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->teams()->destroy('team1');

        expect($result)->toBeTrue();
    });

    it('returns error on destroy team failure', function () {
        Http::fake([
            'https://webexapis.com/v1/teams/team1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ]), 403),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->teams()->destroy('team1');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
