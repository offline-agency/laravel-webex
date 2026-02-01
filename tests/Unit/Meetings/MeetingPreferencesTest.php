<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingPreferences as MeetingPreferencesEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('MeetingPreferences', function () {
    it('gets detail preference', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences*' => Http::response(json_encode((object) [
                'defaultSite' => 'site1',
                'schedulingOptions' => (object) [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $prefs = $laravel_webex->meeting_preferences()->detailPreference();

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('returns error on detail preference failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->meeting_preferences()->detailPreference();

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('gets personal room options', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/personalMeetingRoom*' => Http::response(json_encode((object) [
                'topic' => 'My Room',
                'hostPin' => '1234',
                'enabledAutoLock' => false,
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $prefs = $laravel_webex->meeting_preferences()->detailPersonalRoomOptions();

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('gets audio options', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/audio*' => Http::response(json_encode((object) [
                'audioConnectionOptions' => (object) [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $prefs = $laravel_webex->meeting_preferences()->detailAudioOptions();

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('gets video options', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/video*' => Http::response(json_encode((object) [
                'videoOptions' => (object) [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $prefs = $laravel_webex->meeting_preferences()->detailVideoOptions();

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });
});
