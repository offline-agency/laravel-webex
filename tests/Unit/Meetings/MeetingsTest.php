<?php

namespace Offlineagency\LaravelWebex\Tests\Unit\Meetings;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\Fake\Meetings\MeetingsFakeResponse;
use Offlineagency\LaravelWebex\Tests\TestCase;

class MeetingsTest extends TestCase
{
    public function test_meetings_list()
    {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse())->getMeetingsFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex('fake_bearer');
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

        $laravel_webex = new LaravelWebex('fake_bearer');
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

    public function test_meeting_detail()
    {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse())->getMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex('fake_bearer');
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

        $laravel_webex = new LaravelWebex('fake_bearer');
        $meeting_detail = $laravel_webex->meeting()->detail('fake_id', [
            'current' => false
        ]);

        $this->assertInstanceOf(Meeting::class, $meeting_detail);
        $this->assertEquals('fake_id', $meeting_detail->id);
    }

    public function test_meeting_create()
    {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse())->getNewMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex('fake_bearer');
        $new_meeting = $laravel_webex->meeting()->create('fake_title', 'fake_start', 'fake_end', [
            'agenda' => 'fake_created_agenda',
            'enabledAutoRecordMeeting' => true
        ]);

        $this->assertInstanceOf(Meeting::class, $new_meeting);
        $this->assertEquals('fake_id', $new_meeting->id);
        $this->assertEquals('fake_created_agenda', $new_meeting->agenda);
        $this->assertEquals(true, $new_meeting->enabledAutoRecordMeeting);
    }

    public function test_meeting_update()
    {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse())->getUpdatedMeetingFakeDetail()
            ),
        ]);

        $laravel_webex = new LaravelWebex('fake_bearer');
        $updated_meeting = $laravel_webex->meeting()->update('fake_id', 'fake_title', 'fake_password', 'fake_start', 'fake_end', [
            'agenda' => 'fake_updated_agenda',
            'enabledAutoRecordMeeting' => false
        ]);

        $this->assertInstanceOf(Meeting::class, $updated_meeting);
        $this->assertEquals('fake_id', $updated_meeting->id);
        $this->assertEquals('fake_updated_agenda', $updated_meeting->agenda);
        $this->assertEquals(false, $updated_meeting->enabledAutoRecordMeeting);
    }
}
