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

        $laravel_webex = new LaravelWebex;
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

        $laravel_webex = new LaravelWebex;
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

        $laravel_webex = new LaravelWebex;
        $prefs = $laravel_webex->meeting_preferences()->detailPersonalRoomOptions();

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('gets audio options', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/audio*' => Http::response(json_encode((object) [
                'audioConnectionOptions' => (object) [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $prefs = $laravel_webex->meeting_preferences()->detailAudioOptions();

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('gets video options', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/video*' => Http::response(json_encode((object) [
                'videoOptions' => (object) [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $prefs = $laravel_webex->meeting_preferences()->detailVideoOptions();

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('updates personal room options', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/personalMeetingRoom/*' => Http::response(json_encode((object) [
                'topic' => 'My Room',
                'hostPin' => '1234',
                'enabledAutoLock' => false,
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $prefs = $laravel_webex->meeting_preferences()->updatePersonalRoomOptions(
            'My Room',
            '1234',
            false,
            0,
            false,
            false,
            []
        );

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('updates video options', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/video/*' => Http::response(json_encode((object) [
                'videoDevices' => [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $prefs = $laravel_webex->meeting_preferences()->updateVideoOptions([]);

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('gets scheduling options', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/schedulingOptions*' => Http::response(json_encode((object) [
                'enabledJoinBeforeHost' => true,
                'delegateEmails' => [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $prefs = $laravel_webex->meeting_preferences()->detailSchedulingOptions();

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('updates scheduling options', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/schedulingOptions/*' => Http::response(json_encode((object) [
                'enabledJoinBeforeHost' => true,
                'joinBeforeHostMinutes' => 0,
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $prefs = $laravel_webex->meeting_preferences()->updateSchedulingOptions([], true, 0, false);

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('inserts delegate emails', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/schedulingOptions/delegateEmails/insert/*' => Http::response(json_encode((object) [
                'delegateEmails' => ['d@example.com'],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $prefs = $laravel_webex->meeting_preferences()->insertDelegateEmails(['emails' => ['d@example.com']]);

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('deletes delegate emails', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/schedulingOptions/delegateEmails/delete/*' => Http::response(json_encode((object) [
                'delegateEmails' => [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $prefs = $laravel_webex->meeting_preferences()->deleteDelegateEmails(['emails' => ['d@example.com']]);

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('gets site list', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/sites*' => Http::response(json_encode((object) [
                'sites' => [],
                'defaultSite' => 'https://example.webex.com',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $prefs = $laravel_webex->meeting_preferences()->detailSiteList();

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('updates default site', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/sites/*' => Http::response(json_encode((object) [
                'defaultSite' => true,
                'siteUrl' => 'https://example.webex.com',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $prefs = $laravel_webex->meeting_preferences()->updateDefaultSite(true, 'https://example.webex.com');

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('batch refreshes personal meeting room id', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/personalMeetingRoom/refreshId/*' => Http::response(json_encode((object) [
                'siteUrl' => 'https://example.webex.com',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $prefs = $laravel_webex->meeting_preferences()->batchRefreshPersonalMeetingRoomID('https://example.webex.com');

        expect($prefs)->toBeInstanceOf(MeetingPreferencesEntity::class);
    });

    it('returns error on update personal room options failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingPreferences/personalMeetingRoom/*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ]), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_preferences()->updatePersonalRoomOptions(
            'My Room',
            '1234',
            false,
            0,
            false,
            false,
            []
        );

        expect($result)->toBeInstanceOf(Error::class);
    });
});
