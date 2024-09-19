<?php

namespace Offlineagency\LaravelWebex\Tests\Unit\Meetings;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingParticipant;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\Fake\Meetings\MeetingParticipantsFakeResponse;
use Offlineagency\LaravelWebex\Tests\TestCase;

class MeetingParticipantsTest extends TestCase
{
    /* list */
    public function test_meeting_participants_list()
    {
        Http::fake([
            'meetingParticipants?meetingId=fake_id' => Http::response(
                (new MeetingParticipantsFakeResponse())->getMeetingParticipantsFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_participants_list = $laravel_webex->meeting_participants()->list('fake_id');

        $this->assertCount(2, $meeting_participants_list);

        $single_meeting_participant = null;
        foreach ($meeting_participants_list as $meeting_participant) {
            $this->assertInstanceOf(MeetingParticipant::class, $meeting_participant);
            $single_meeting_participant = $meeting_participant;
        }

        $this->assertEquals('fake_id', $single_meeting_participant->id);
    }

    public function test_filtered_meeting_participants_list()
    {
        Http::fake([
            'meetingParticipants?meetingId=fake_id&hostEmail=fake_email' => Http::response(
                (new MeetingParticipantsFakeResponse())->getMeetingParticipantsFakeList()
            ),
        ]);

        //hostEmail
        $laravel_webex = new LaravelWebex();
        $meeting_participants_list = $laravel_webex->meeting_participants()->list('fake_id', [
            'hostEmail' => 'fake_email',
        ]);

        $this->assertCount(2, $meeting_participants_list);

        $single_meeting_participant = null;
        foreach ($meeting_participants_list as $meeting_participant) {
            $this->assertInstanceOf(MeetingParticipant::class, $meeting_participant);
            $single_meeting_participant = $meeting_participant;
        }

        $this->assertEquals('fake_id', $single_meeting_participant->id);
        $this->assertEquals('fake_hostEmail', $single_meeting_participant->hostEmail);
    }

    public function test_error_on_meeting_list()
    {
        Http::fake([
            'meetingParticipants?meetingId=fake_id' => Http::response(
                (new MeetingParticipantsFakeResponse())->getErrorOnMeetingsFakeList(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting_participants_list = $laravel_webex->meeting_participants()->list('fake_id');

        $this->assertInstanceOf(Error::class, $error_meeting_participants_list);
        $this->assertEquals('fake_message', $error_meeting_participants_list->message);
        $this->assertIsArray($error_meeting_participants_list->errors);
        $this->assertEquals('fake_trackingId', $error_meeting_participants_list->trackingId);
    }

    /* detail */

    public function test_meeting_participants_detail()
    {
        Http::fake([
            'meetingParticipants/fake_id' => Http::response(
                (new MeetingParticipantsFakeResponse())->getMeetingParticipantsFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_participants_detail = $laravel_webex->meeting_participants()->detail('fake_id');

        $this->assertInstanceOf(MeetingParticipant::class, $meeting_participants_detail);
        $this->assertEquals('fake_id', $meeting_participants_detail->id);
    }

    public function test_meeting_participants_query_with_email()
    {
        Http::fake([
            'meetingParticipants/query' => Http::response(
                (new MeetingParticipantsFakeResponse())->getMeetingParticipantsFakeQueryWithEmail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_participants_detail = $laravel_webex->meeting_participants()->queryWIthEmail('fake_id');

        $this->assertInstanceOf(MeetingParticipant::class, $meeting_participants_detail);
        $this->assertEquals('fake_id', $meeting_participants_detail->id);
    }

    public function test_meeting_participants_update()
    {
        Http::fake([
            'meetingParticipants/fake_id' => Http::response(
                (new MeetingParticipantsFakeResponse())->getUpdatedMeetingParticipantsFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_participants_detail = $laravel_webex->meeting_participants()->update('fake_id');

        $this->assertInstanceOf(MeetingParticipant::class, $meeting_participants_detail);
        $this->assertEquals('fake_id', $meeting_participants_detail->id);
    }

    public function test_meeting_participants_admit()
    {
        Http::fake([
            'meetingParticipants/admit' => Http::response(
                (new MeetingParticipantsFakeResponse())->getAdmittedMeetingParticipantsFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_participants_detail = $laravel_webex->meeting_participants()->admit();

        $this->assertInstanceOf(MeetingParticipant::class, $meeting_participants_detail);
        $this->assertEquals('fake_id', $meeting_participants_detail->id);
    }
}
