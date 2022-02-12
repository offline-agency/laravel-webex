<?php

namespace Offlineagency\LaravelWebex\Tests\Unit;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Meetings;
use Offlineagency\LaravelWebex\Tests\Fake\MeetingsFakeResponse;
use Offlineagency\LaravelWebex\Tests\TestCase;

class MeetingsTest extends TestCase
{
    public function test_meetings_list()
    {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeList()
            ),
        ]);

        $meetings = new Meetings;
        $meetings_list = $meetings->meetings();

        $this->assertCount(2, $meetings_list);

        $first_meeting = $meetings_list[0];
        $this->assertIsObject($first_meeting);
        $this->assertObjectHasAttribute('id', $first_meeting);
        $this->assertEquals('fake_id', $first_meeting->id);

        $second_meeting = $meetings_list[1];
        $this->assertIsObject($second_meeting);
        $this->assertObjectHasAttribute('enabledAutoRecordMeeting', $second_meeting);
        $this->assertTrue($second_meeting->enabledAutoRecordMeeting);

        $meetings = new Meetings;
        $meetings_list = $meetings->meetings([], 'complete');

        $this->assertIsObject($meetings_list);
        $this->assertObjectHasAttribute('items', $meetings_list);

        $meetings = new Meetings;
        $meetings_list = $meetings->meetings([], 'original');

        $this->assertJson($meetings_list);
    }

    public function test_meeting_detail()
    {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse)->getMeetingFakeDetail()
            ),
        ]);

        $meetings = new Meetings;
        $meeting_detail = $meetings->meeting([
            'id' => 'fake_id'
        ]);

        $this->assertIsObject($meeting_detail);
        $this->assertObjectHasAttribute('id', $meeting_detail);
        $this->assertEquals('fake_id', $meeting_detail->id);
    }
}
