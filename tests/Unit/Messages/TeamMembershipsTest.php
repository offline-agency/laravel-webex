<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\TeamMembership as TeamMembershipEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('TeamMemberships', function () {
    it('lists team memberships', function () {
        Http::fake([
            'https://webexapis.com/v1/team/memberships*' => Http::response(json_encode((object) [
                'items' => [
                    (object) [
                        'id' => 'tm1',
                        'teamId' => 'team1',
                        'personId' => 'p1',
                        'personEmail' => 'user@example.com',
                        'personDisplayName' => 'User',
                        'isModerator' => false,
                        'created' => '2024-01-01T00:00:00Z',
                    ],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $list = $laravel_webex->team_memberships()->list(['teamId' => 'team1']);

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(TeamMembershipEntity::class);
        expect($list[0]->id)->toEqual('tm1');
        expect($list[0]->teamId)->toEqual('team1');
    });

    it('returns error on team memberships list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/team/memberships*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->team_memberships()->list();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('creates team membership', function () {
        Http::fake([
            'https://webexapis.com/v1/team/memberships*' => Http::response(json_encode((object) [
                'id' => 'tm1',
                'teamId' => 'team1',
                'personId' => 'p1',
                'personEmail' => 'user@example.com',
                'personDisplayName' => 'User',
                'isModerator' => false,
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $membership = $laravel_webex->team_memberships()->create('team1', 'user@example.com');

        expect($membership)->toBeInstanceOf(TeamMembershipEntity::class);
        expect($membership->id)->toEqual('tm1');
        expect($membership->teamId)->toEqual('team1');
    });

    it('gets team membership detail', function () {
        Http::fake([
            'https://webexapis.com/v1/team/memberships/tm1*' => Http::response(json_encode((object) [
                'id' => 'tm1',
                'teamId' => 'team1',
                'personId' => 'p1',
                'personEmail' => 'user@example.com',
                'isModerator' => false,
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $membership = $laravel_webex->team_memberships()->detail('tm1');

        expect($membership)->toBeInstanceOf(TeamMembershipEntity::class);
        expect($membership->id)->toEqual('tm1');
    });

    it('updates team membership', function () {
        Http::fake([
            'https://webexapis.com/v1/team/memberships/tm1*' => Http::response(json_encode((object) [
                'id' => 'tm1',
                'teamId' => 'team1',
                'personId' => 'p1',
                'personEmail' => 'user@example.com',
                'isModerator' => true,
                'created' => '2024-01-01T00:00:00Z',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $membership = $laravel_webex->team_memberships()->update('tm1', ['isModerator' => true]);

        expect($membership)->toBeInstanceOf(TeamMembershipEntity::class);
        expect($membership->isModerator)->toBeTrue();
    });

    it('destroys team membership', function () {
        Http::fake([
            'https://webexapis.com/v1/team/memberships/tm1*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->team_memberships()->destroy('tm1');

        expect($result)->toBeTrue();
    });
});
