<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting as MeetingEntity;
use Offlineagency\LaravelWebex\Entities\Meetings\SessionTypes as SessionTypesEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('SessionTypes', function () {
    it('lists site session types', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/sessionTypes*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'st1', 'name' => 'Webex Meetings'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->session_types()->listSiteSessionTypes();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(SessionTypesEntity::class);
    });

    it('returns error on list site session types failure', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/config/sessionTypes*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->session_types()->listSiteSessionTypes();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('lists user session types', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/userconfig/sessionTypes*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'st1', 'name' => 'Webex Meetings'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->session_types()->listUserSessionType();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(SessionTypesEntity::class);
    });

    it('updates user session types', function () {
        Http::fake([
            'https://webexapis.com/v1/admin/meeting/userconfig/sessionTypes*' => Http::response(json_encode((object) [
                'id' => 'st1',
                'sessionTypeIds' => ['st1'],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->session_types()->update('https://example.webex.com', ['st1']);

        expect($result)->toBeInstanceOf(MeetingEntity::class);
    });
});
