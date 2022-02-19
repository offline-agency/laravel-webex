<?php

namespace Offlineagency\LaravelWebex\Tests\Unit\Meetings;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\Fake\Meetings\MeetingsFakeResponse;
use Offlineagency\LaravelWebex\Tests\TestCase;

class MeetingsTest extends TestCase
{
    /* list */

    public function test_meetings_list()
    {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meetings_list = $laravel_webex->meeting()->list();

        $this->assertCount(2, $meetings_list);

        $single_meeting = null;
        foreach ($meetings_list as $meeting) {
            $this->assertInstanceOf(Meeting::class, $meeting);
            $single_meeting = $meeting;
        }

        $this->assertEquals('fake_id', $single_meeting->id);
    }

    public function test_filtered_meetings_list()
    {
        Http::fake([
            'meetings?state=inProgress' => Http::response(
                (new MeetingsFakeResponse())->getFilteredMeetingsFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meetings_list = $laravel_webex->meeting()->list([
            'state' => 'inProgress'
        ]);

        $this->assertCount(1, $meetings_list);

        $single_meeting = null;
        foreach ($meetings_list as $meeting) {
            $this->assertInstanceOf(Meeting::class, $meeting);
            $single_meeting = $meeting;
        }

        $this->assertEquals('fake_id', $single_meeting->id);
    }

    public function test_error_on_meeting_list()
    {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeList(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $error_meeting = $laravel_webex->meeting()->list();

        $this->assertInstanceOf(Error::class, $error_meeting);
        $this->assertEquals('fake_message', $error_meeting->message);
        $this->assertIsArray($error_meeting->errors);
        $this->assertEquals('fake_trackingId', $error_meeting->trackingId);
    }

    /* detail */

    public function test_meeting_detail()
    {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse())->getMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_detail = $laravel_webex->meeting()->detail('fake_id');

        $this->assertInstanceOf(Meeting::class, $meeting_detail);
        $this->assertEquals('fake_id', $meeting_detail->id);
    }

    public function test_filtered_meeting_detail()
    {
        Http::fake([
            'meetings/fake_id?current=0' => Http::response(
                (new MeetingsFakeResponse())->getMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $meeting_detail = $laravel_webex->meeting()->detail('fake_id', [
            'current' => false
        ]);

        $this->assertInstanceOf(Meeting::class, $meeting_detail);
        $this->assertEquals('fake_id', $meeting_detail->id);
    }

    /* create */

    public function test_meeting_create()
    {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse())->getNewMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $new_meeting = $laravel_webex->meeting()->create('fake_title', 'fake_start', 'fake_end', [
            'agenda' => 'fake_created_agenda',
            'enabledAutoRecordMeeting' => true
        ]);

        $this->assertInstanceOf(Meeting::class, $new_meeting);
        $this->assertEquals('fake_id', $new_meeting->id);
        $this->assertEquals('fake_created_agenda', $new_meeting->agenda);
        $this->assertEquals(true, $new_meeting->enabledAutoRecordMeeting);
    }

    /* update */

    public function test_meeting_update()
    {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse())->getUpdatedMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $updated_meeting = $laravel_webex->meeting()->update('fake_id', 'fake_title', 'fake_password', 'fake_start', 'fake_end', [
            'agenda' => 'fake_updated_agenda',
            'enabledAutoRecordMeeting' => false
        ]);

        $this->assertInstanceOf(Meeting::class, $updated_meeting);
        $this->assertEquals('fake_id', $updated_meeting->id);
        $this->assertEquals('fake_updated_agenda', $updated_meeting->agenda);
        $this->assertEquals(false, $updated_meeting->enabledAutoRecordMeeting);
    }

    /* delete */

    public function test_meeting_delete()
    {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse())->getDeleteMeetingFakeResponse()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $delete_response = $laravel_webex->meeting()->destroy('fake_id');

        $this->assertEquals('Meeting deleted', $delete_response);
    }

    public function test_meeting_delete_without_mail()
    {
        Http::fake([
            'meetings/fake_id?sendEmail=0' => Http::response(
                (new MeetingsFakeResponse())->getDeleteMeetingFakeResponse()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $delete_response = $laravel_webex->meeting()->destroy('fake_id', [
            'sendEmail' => false
        ]);

        $this->assertEquals('Meeting deleted', $delete_response);
    }

    public function test_error_on_meeting_delete()
    {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse())->getErrorOnMeetingsFakeList(),
                401
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $delete_response = $laravel_webex->meeting()->destroy('fake_id');

        $this->assertInstanceOf(Error::class, $delete_response);
        $this->assertEquals('fake_message', $delete_response->message);
        $this->assertIsArray($delete_response->errors);
        $this->assertEquals('fake_trackingId', $delete_response->trackingId);
    }
}
