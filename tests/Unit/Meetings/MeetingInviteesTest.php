<?php

namespace Offlineagency\LaravelWebex\Tests\Unit\Meetings;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingInvitee;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\Fake\Meetings\MeetingInviteesFakeResponse;
use Offlineagency\LaravelWebex\Tests\TestCase;

class MeetingInviteesTest extends TestCase
{
    public function test_meeting_invitees_list()
    {
        Http::fake([
            'meetingInvitees?meetingId=fake_id' => Http::response(
                (new MeetingInviteesFakeResponse())->getMeetingInviteesFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_invitees_list = $laravel_webex->meeting_invitees()->list('fake_id');

        $this->assertCount(2, $meeting_invitees_list);

        $single_meeting_invitee = null;
        foreach ($meeting_invitees_list as $meeting_invitee) {
            $this->assertInstanceOf(MeetingInvitee::class, $meeting_invitee);
            $single_meeting_invitee = $meeting_invitee;
        }

        $this->assertEquals('fake_id', $single_meeting_invitee->id);
    }

    public function test_meeting_invitee_detail()
    {
        Http::fake([
            'meetingInvitees/fake_id' => Http::response(
                (new MeetingInviteesFakeResponse())->getMeetingInviteesFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_invitee_detail = $laravel_webex->meeting_invitees()->detail('fake_id');

        $this->assertInstanceOf(MeetingInvitee::class, $meeting_invitee_detail);
        $this->assertEquals('fake_id', $meeting_invitee_detail->id);
    }

    public function test_meeting_invitee_create()
    {
        Http::fake([
            'meetingInvitees' => Http::response(
                (new MeetingInviteesFakeResponse())->getNewMeetingInviteeFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $new_meeting_invitee = $laravel_webex->meeting_invitees()->create('fake_id', 'fake_email');

        $this->assertInstanceOf(MeetingInvitee::class, $new_meeting_invitee);
        $this->assertEquals('fake_id', $new_meeting_invitee->id);
        $this->assertEquals('fake_email', $new_meeting_invitee->email);
    }

    public function test_meeting_invitee_bulk_create()
    {
        Http::fake([
            'meetingInvitees/bulkInsert' => Http::response(
                (new MeetingInviteesFakeResponse())->getNewMeetingInviteesFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $new_meeting_invitees = $laravel_webex->meeting_invitees()->bulk_create('fake_id', [
            (object)[
                'email' => 'fake_email_one'
            ],
            (object)[
                'email' => 'fake_email_two'
            ]
        ]);

        $this->assertCount(2, $new_meeting_invitees);

        $single_meeting_invitee = null;
        foreach ($new_meeting_invitees as $new_meeting_invitee) {
            $this->assertInstanceOf(MeetingInvitee::class, $new_meeting_invitee);
            $single_meeting_invitee = $new_meeting_invitee;
        }

        $this->assertEquals('fake_id', $single_meeting_invitee->id);
    }

    public function test_meeting_invitee_update()
    {
        Http::fake([
            'meetingInvitees/fake_id' => Http::response(
                (new MeetingInviteesFakeResponse())->getUpdatedMeetingInviteeFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $updated_meeting = $laravel_webex->meeting_invitees()->update('fake_id', 'fake_email');

        $this->assertInstanceOf(MeetingInvitee::class, $updated_meeting);
        $this->assertEquals('fake_id', $updated_meeting->id);
        $this->assertEquals('fake_email', $updated_meeting->email);
    }

    public function test_meeting_invitee_delete()
    {
        Http::fake([
            'meetingInvitees/fake_id' => Http::response(
                (new MeetingInviteesFakeResponse())->getDeleteMeetingInviteeFakeResponse()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $delete_response = $laravel_webex->meeting_invitees()->destroy('fake_id');

        $this->assertEquals('Meeting invitee deleted', $delete_response);
    }
}
