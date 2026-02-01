<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingInvitee;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('Meeting Invitees', function () {
    it('lists meeting invitees', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'fake_id', 'email' => 'fake_email'], (object) ['id' => 'fake_id', 'email' => 'fake_email']],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $meeting_invitees_list = $laravel_webex->meeting_invitees()->list('fake_id');

        expect($meeting_invitees_list)->toHaveCount(2);

        $single_meeting_invitee = null;
        foreach ($meeting_invitees_list as $meeting_invitee) {
            expect($meeting_invitee)->toBeInstanceOf(MeetingInvitee::class);
            $single_meeting_invitee = $meeting_invitee;
        }

        expect($single_meeting_invitee->id)->toEqual('fake_id');
    });

    it('gets meeting invitee detail', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees/fake_id*' => Http::response(json_encode((object) [
                'id' => 'fake_id',
                'email' => 'fake_email',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $meeting_invitee_detail = $laravel_webex->meeting_invitees()->detail('fake_id');

        expect($meeting_invitee_detail)->toBeInstanceOf(MeetingInvitee::class);
        expect($meeting_invitee_detail->id)->toEqual('fake_id');
    });

    it('creates meeting invitee', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees' => Http::response(json_encode((object) [
                'id' => 'fake_id',
                'email' => 'fake_email',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $new_meeting_invitee = $laravel_webex->meeting_invitees()->create('fake_id', 'fake_email');

        expect($new_meeting_invitee)->toBeInstanceOf(MeetingInvitee::class);
        expect($new_meeting_invitee->id)->toEqual('fake_id');
        expect($new_meeting_invitee->email)->toEqual('fake_email');
    });

    it('bulk creates meeting invitees', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees/bulkInsert*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'fake_id', 'email' => 'fake_email'], (object) ['id' => 'fake_id', 'email' => 'fake_email']],
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $new_meeting_invitees = $laravel_webex->meeting_invitees()->bulk_create('fake_id', [
            (object) ['email' => 'fake_email_one'],
            (object) ['email' => 'fake_email_two'],
        ]);

        expect($new_meeting_invitees)->toHaveCount(2);

        $single_meeting_invitee = null;
        foreach ($new_meeting_invitees as $new_meeting_invitee) {
            expect($new_meeting_invitee)->toBeInstanceOf(MeetingInvitee::class);
            $single_meeting_invitee = $new_meeting_invitee;
        }

        expect($single_meeting_invitee->id)->toEqual('fake_id');
    });

    it('updates meeting invitee', function () {
        Http::fake([
            'https://webexapis.com/v1/meetingInvitees/fake_id*' => Http::response(json_encode((object) [
                'id' => 'fake_id',
                'email' => 'fake_email',
            ])),
        ]);

        $laravel_webex = new LaravelWebex;
        $updated_meeting = $laravel_webex->meeting_invitees()->update('fake_id', 'fake_email');

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
});
