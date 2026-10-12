<?php

use Illuminate\Http\Client\Request;
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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->teams()->list();
        assert(is_array($list));

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
            ], JSON_THROW_ON_ERROR), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->teams()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('creates team', function () {
        Http::fake([
            'https://webexapis.com/v1/teams*' => Http::response(json_encode((object) [
                'id' => 'team1',
                'name' => 'New Team',
                'creatorId' => 'u1',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $team = $laravel_webex->teams()->create('New Team');
        assert($team instanceof TeamEntity);

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
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $team = $laravel_webex->teams()->detail('team1');
        assert($team instanceof TeamEntity);

        expect($team)->toBeInstanceOf(TeamEntity::class);
        expect($team->id)->toEqual('team1');
    });

    it('updates team', function () {
        Http::fake([
            'https://webexapis.com/v1/teams/team1*' => Http::response(json_encode((object) [
                'id' => 'team1',
                'name' => 'Updated Team',
                'creatorId' => 'u1',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $team = $laravel_webex->teams()->update('team1', 'Updated Team');
        assert($team instanceof TeamEntity);

        expect($team)->toBeInstanceOf(TeamEntity::class);
        expect($team->name)->toEqual('Updated Team');
    });

    it('destroys team', function () {
        Http::fake([
            'https://webexapis.com/v1/teams/team1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->teams()->destroy('team1');

        expect($result)->toBeTrue();
    });

    it('returns error on destroy team failure', function () {
        Http::fake([
            'https://webexapis.com/v1/teams/team1*' => Http::response(json_encode((object) [
                'message' => 'Forbidden',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 403),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->teams()->destroy('team1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('creates team with null additional data', function () {
        Http::fake([
            'https://webexapis.com/v1/teams*' => Http::response(json_encode((object) [
                'id' => 'team2',
                'name' => 'Team A',
                'creatorId' => 'u1',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $team = $laravel_webex->teams()->create('Team A', null);
        assert($team instanceof TeamEntity);

        expect($team)->toBeInstanceOf(TeamEntity::class);
        expect($team->name)->toEqual('Team A');
        Http::assertSent(fn (Request $request) => $request['name'] === 'Team A');
    });

    it('returns error on create team failure', function () {
        Http::fake([
            'https://webexapis.com/v1/teams*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->teams()->create('New Team');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Bad Request');
    });

    it('returns error on team detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/teams/team1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->teams()->detail('team1');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });

    it('returns error on update team failure', function () {
        Http::fake([
            'https://webexapis.com/v1/teams/team1*' => Http::response(json_encode((object) [
                'message' => 'Not Found',
                'errors' => [],
                'trackingId' => 't1',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->teams()->update('team1', 'Updated Team');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('Not Found');
    });
});
