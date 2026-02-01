<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingParticipant;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\Fake\Meetings\MeetingParticipantsFakeResponse;

describe('Meeting Participants', function () {
    it('lists meeting participants', function () {
        Http::fake([
            'meetingParticipants?meetingId=fake_id' => Http::response(
                (new MeetingParticipantsFakeResponse())->getMeetingParticipantsFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_participants_list = $laravel_webex->meeting_participants()->list('fake_id');

        expect($meeting_participants_list)->toHaveCount(2);

        $single_meeting_participant = null;
        foreach ($meeting_participants_list as $meeting_participant) {
            expect($meeting_participant)->toBeInstanceOf(MeetingParticipant::class);
            $single_meeting_participant = $meeting_participant;
        }

        expect($single_meeting_participant->id)->toEqual('fake_id');
    });

    it('lists filtered meeting participants', function () {
        Http::fake([
            'meetingParticipants?meetingId=fake_id&hostEmail=fake_email' => Http::response(
                (new MeetingParticipantsFakeResponse())->getMeetingParticipantsFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_participants_list = $laravel_webex->meeting_participants()->list('fake_id', [
            'hostEmail' => 'fake_email',
        ]);

        expect($meeting_participants_list)->toHaveCount(2);

        $single_meeting_participant = null;
        foreach ($meeting_participants_list as $meeting_participant) {
            expect($meeting_participant)->toBeInstanceOf(MeetingParticipant::class);
            $single_meeting_participant = $meeting_participant;
        }

        expect($single_meeting_participant->id)->toEqual('fake_id');
        expect($single_meeting_participant->hostEmail)->toEqual('fake_hostEmail');
    });

    it('returns error on meeting participants list failure', function () {
        Http::fake([
            'meetingParticipants?meetingId=fake_id' => Http::response(
                (new MeetingParticipantsFakeResponse())->getErrorOnMeetingsFakeList(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting_participants_list = $laravel_webex->meeting_participants()->list('fake_id');

        expect($error_meeting_participants_list)->toBeInstanceOf(Error::class);
        expect($error_meeting_participants_list->message)->toEqual('fake_message');
        expect($error_meeting_participants_list->errors)->toBeArray();
        expect($error_meeting_participants_list->trackingId)->toEqual('fake_trackingId');
    });

    it('gets meeting participants detail', function () {
        Http::fake([
            'meetingParticipants/fake_id' => Http::response(
                (new MeetingParticipantsFakeResponse())->getMeetingParticipantsFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_participants_detail = $laravel_webex->meeting_participants()->detail('fake_id');

        expect($meeting_participants_detail)->toBeInstanceOf(MeetingParticipant::class);
        expect($meeting_participants_detail->id)->toEqual('fake_id');
    });

    it('queries meeting participants with email', function () {
        Http::fake([
            'meetingParticipants/query' => Http::response(
                (new MeetingParticipantsFakeResponse())->getMeetingParticipantsFakeQueryWithEmail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_participants_detail = $laravel_webex->meeting_participants()->queryWIthEmail('fake_id');

        expect($meeting_participants_detail)->toBeInstanceOf(MeetingParticipant::class);
        expect($meeting_participants_detail->id)->toEqual('fake_id');
    });

    it('updates meeting participants', function () {
        Http::fake([
            'meetingParticipants/fake_id' => Http::response(
                (new MeetingParticipantsFakeResponse())->getUpdatedMeetingParticipantsFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_participants_detail = $laravel_webex->meeting_participants()->update('fake_id');

        expect($meeting_participants_detail)->toBeInstanceOf(MeetingParticipant::class);
        expect($meeting_participants_detail->id)->toEqual('fake_id');
    });

    it('admits meeting participants', function () {
        Http::fake([
            'meetingParticipants/admit' => Http::response(
                (new MeetingParticipantsFakeResponse())->getAdmittedMeetingParticipantsFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_participants_detail = $laravel_webex->meeting_participants()->admit();

        expect($meeting_participants_detail)->toBeInstanceOf(MeetingParticipant::class);
        expect($meeting_participants_detail->id)->toEqual('fake_id');
    });

    it('returns error on meeting participants query failure', function () {
        Http::fake([
            'meetingParticipants/query' => Http::response(
                (new MeetingParticipantsFakeResponse())->getErrorOnFakeQueryWithEmail(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting_participants_detail = $laravel_webex->meeting_participants()->queryWIthEmail('fake_id');

        expect($error_meeting_participants_detail)->toBeInstanceOf(Error::class);
        expect($error_meeting_participants_detail->message)->toEqual('fake_message');
        expect($error_meeting_participants_detail->errors)->toBeArray();
        expect($error_meeting_participants_detail->trackingId)->toEqual('fake_trackingId');
    });

    it('returns error on update failure', function () {
        Http::fake([
            'meetingParticipants/fake_id' => Http::response(
                (new MeetingParticipantsFakeResponse())->getErrorOnFakeUpdate(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting_participants_detail = $laravel_webex->meeting_participants()->update('fake_id');

        expect($error_meeting_participants_detail)->toBeInstanceOf(Error::class);
        expect($error_meeting_participants_detail->message)->toEqual('fake_message');
        expect($error_meeting_participants_detail->errors)->toBeArray();
        expect($error_meeting_participants_detail->trackingId)->toEqual('fake_trackingId');
    });

    it('returns error on admit failure', function () {
        Http::fake([
            'meetingParticipants/admit' => Http::response(
                (new MeetingParticipantsFakeResponse())->getErrorOnFakeAdmit(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting_participants_detail = $laravel_webex->meeting_participants()->admit();

        expect($error_meeting_participants_detail)->toBeInstanceOf(Error::class);
        expect($error_meeting_participants_detail->message)->toEqual('fake_message');
        expect($error_meeting_participants_detail->errors)->toBeArray();
        expect($error_meeting_participants_detail->trackingId)->toEqual('fake_trackingId');
    });
});
