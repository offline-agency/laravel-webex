<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingInvitee;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Meeting Invitees', function () {
    it('lists meeting invitees', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'fake_id', 'email' => 'fake_email'], (object) ['id' => 'fake_id', 'email' => 'fake_email']],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $meeting_invitees_list = $laravel_webex->meeting_invitees()->list('fake_id');
        assert(is_array($meeting_invitees_list));

        expect($meeting_invitees_list)->toHaveCount(2);

        $single_meeting_invitee = null;
        foreach ($meeting_invitees_list as $meeting_invitee) {
            expect($meeting_invitee)->toBeInstanceOf(MeetingInvitee::class);
            $single_meeting_invitee = $meeting_invitee;
        }

        assert($single_meeting_invitee !== null);
        expect($single_meeting_invitee->id)->toEqual('fake_id');
    });

    it('gets meeting invitee detail', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees/fake_id*' => Http::response(json_encode((object) [
                'id' => 'fake_id',
                'email' => 'fake_email',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $meeting_invitee_detail = $laravel_webex->meeting_invitees()->detail('fake_id');
        assert($meeting_invitee_detail instanceof MeetingInvitee);

        expect($meeting_invitee_detail)->toBeInstanceOf(MeetingInvitee::class);
        expect($meeting_invitee_detail->id)->toEqual('fake_id');
    });

    it('creates meeting invitee', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees' => Http::response(json_encode((object) [
                'id' => 'fake_id',
                'email' => 'fake_email',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $new_meeting_invitee = $laravel_webex->meeting_invitees()->create('fake_id', 'fake_email');
        assert($new_meeting_invitee instanceof MeetingInvitee);

        expect($new_meeting_invitee)->toBeInstanceOf(MeetingInvitee::class);
        expect($new_meeting_invitee->id)->toEqual('fake_id');
        expect($new_meeting_invitee->email)->toEqual('fake_email');
    });

    it('bulk creates meeting invitees', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees/bulkInsert*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'fake_id', 'email' => 'fake_email'], (object) ['id' => 'fake_id', 'email' => 'fake_email']],
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $new_meeting_invitees = $laravel_webex->meeting_invitees()->bulk_create('fake_id', [
            'items' => [
                ['email' => 'fake_email_one'],
                ['email' => 'fake_email_two'],
            ],
        ]);

        Http::assertSent(fn (Request $request) => $request['meetingId'] === 'fake_id'
            && $request['items'] === [['email' => 'fake_email_one'], ['email' => 'fake_email_two']]);
        assert(is_array($new_meeting_invitees));

        expect($new_meeting_invitees)->toHaveCount(2);

        $single_meeting_invitee = null;
        foreach ($new_meeting_invitees as $new_meeting_invitee) {
            expect($new_meeting_invitee)->toBeInstanceOf(MeetingInvitee::class);
            $single_meeting_invitee = $new_meeting_invitee;
        }

        assert($single_meeting_invitee !== null);
        expect($single_meeting_invitee->id)->toEqual('fake_id');
    });

    it('updates meeting invitee', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees/fake_id*' => Http::response(json_encode((object) [
                'id' => 'fake_id',
                'email' => 'fake_email',
            ], JSON_THROW_ON_ERROR)),
        ]);

        $laravel_webex = new LaravelWebex;
        $updated_meeting = $laravel_webex->meeting_invitees()->update('fake_id', 'fake_email');
        assert($updated_meeting instanceof MeetingInvitee);

        expect($updated_meeting)->toBeInstanceOf(MeetingInvitee::class);
        expect($updated_meeting->id)->toEqual('fake_id');
        expect($updated_meeting->email)->toEqual('fake_email');
    });

    it('deletes meeting invitee', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees/fake_id*' => Http::response('', 204),
        ]);

        $laravel_webex = new LaravelWebex;
        $delete_response = $laravel_webex->meeting_invitees()->destroy('fake_id');

        expect($delete_response)->toEqual('Meeting invitee deleted');
    });

    it('returns error on meeting invitees list failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees*' => Http::response(json_encode((object) [
                'message' => 'fake_message',
                'errors' => [],
                'trackingId' => 'fake_trackingId',
            ], JSON_THROW_ON_ERROR), 401),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_invitees()->list('fake_id');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('fake_message');
        expect($result->errors)->toBeArray();
        expect($result->trackingId)->toEqual('fake_trackingId');
    });

    it('returns error on meeting invitee detail failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees/fake_id*' => Http::response(json_encode((object) [
                'message' => 'fake_message',
                'errors' => [],
                'trackingId' => 'fake_trackingId',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_invitees()->detail('fake_id');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('fake_message');
        expect($result->errors)->toBeArray();
        expect($result->trackingId)->toEqual('fake_trackingId');
    });

    it('returns error on meeting invitee create failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees' => Http::response(json_encode((object) [
                'message' => 'fake_message',
                'errors' => [],
                'trackingId' => 'fake_trackingId',
            ], JSON_THROW_ON_ERROR), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_invitees()->create('fake_id', 'fake_email');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('fake_message');
        expect($result->errors)->toBeArray();
        expect($result->trackingId)->toEqual('fake_trackingId');
    });

    it('returns error on meeting invitees bulk create failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees/bulkInsert*' => Http::response(json_encode((object) [
                'message' => 'fake_message',
                'errors' => [],
                'trackingId' => 'fake_trackingId',
            ], JSON_THROW_ON_ERROR), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_invitees()->bulk_create('fake_id');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('fake_message');
        expect($result->errors)->toBeArray();
        expect($result->trackingId)->toEqual('fake_trackingId');
    });

    it('returns error on meeting invitee update failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees/fake_id*' => Http::response(json_encode((object) [
                'message' => 'fake_message',
                'errors' => [],
                'trackingId' => 'fake_trackingId',
            ], JSON_THROW_ON_ERROR), 400),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_invitees()->update('fake_id', 'fake_email');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('fake_message');
        expect($result->errors)->toBeArray();
        expect($result->trackingId)->toEqual('fake_trackingId');
    });

    it('returns error on meeting invitee delete failure', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees/fake_id*' => Http::response(json_encode((object) [
                'message' => 'fake_message',
                'errors' => [],
                'trackingId' => 'fake_trackingId',
            ], JSON_THROW_ON_ERROR), 404),
        ]);

        $laravel_webex = new LaravelWebex;
        $result = $laravel_webex->meeting_invitees()->destroy('fake_id');
        assert($result instanceof Error);

        expect($result)->toBeInstanceOf(Error::class);
        expect($result->message)->toEqual('fake_message');
        expect($result->errors)->toBeArray();
        expect($result->trackingId)->toEqual('fake_trackingId');
    });
});
