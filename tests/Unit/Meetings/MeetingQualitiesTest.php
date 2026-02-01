<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingQualities as MeetingQualitiesEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('MeetingQualities', function () {
    it('gets meeting qualities detail', function () {
        Http::fake([
            'https://webexapis.com/v1/meeting/qualities*' => Http::response(json_encode((object) [
                'meetingId' => 'm1',
                'qualities' => [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->meeting_qualities()->detailQualities('m1');

        expect($result)->toBeInstanceOf(MeetingQualitiesEntity::class);
    });

    it('returns error on detail qualities failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meeting/qualities*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->meeting_qualities()->detailQualities('m1');

        expect($result)->toBeInstanceOf(Error::class);
    });
});
