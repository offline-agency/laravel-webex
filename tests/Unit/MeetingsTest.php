<?php

namespace Offlineagency\LaravelWebex\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Support\Arr;
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
        $meetings_list = $meetings->meetings([], [], 'complete');

        $this->assertIsObject($meetings_list);
        $this->assertObjectHasAttribute('items', $meetings_list);

        $meetings = new Meetings;
        $meetings_list = $meetings->meetings([], [], 'original');

        $this->assertJson($meetings_list);
    }

    public function test_filtered_meetings_list()
    {
        Http::fake([
            'meetings?state=fake_state' => Http::response(
                (new MeetingsFakeResponse)->getFilteredMeetingsFakeList()
            ),
        ]);

        $meetings = new Meetings;
        $meetings_list = $meetings->meetings([], [
            'state' => 'fake_state'
        ]);

        $this->assertCount(1, $meetings_list);

        $meeting = $meetings_list[0];
        $this->assertIsObject($meeting);
        $this->assertObjectHasAttribute('id', $meeting);
        $this->assertEquals('fake_state', $meeting->state);
    }

    public function test_create_meeting()
    {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse)->getMeetingFakeDetail()
            ),
        ]);

        $meetings = new Meetings;
        $new_meeting = $meetings->createMeeting([
            'title' => 'Riunione API',
            'end' => Carbon::now()->addDay()->addMinutes(20)->toDateTimeString()
        ]);

        $this->assertIsArray($new_meeting);
        $this->assertArrayHasKey('start', $new_meeting);
        $this->assertEquals(
            'The start field is required.',
            Arr::get($new_meeting, 'start')[0]
        );

        $meetings = new Meetings;
        $new_meeting = $meetings->createMeeting([
            'title' => 'Riunione API',
            'start' => Carbon::now()->addDay()->toDateTimeString(),
            'end' => Carbon::now()->addDay()->addMinutes(20)->toDateTimeString()
        ]);

        $this->assertIsObject($new_meeting);
        $this->assertObjectHasAttribute('id', $new_meeting);
        $this->assertEquals('fake_id', $new_meeting->id);
    }

    public function test_meeting_detail()
    {
        Http::fake([
            'meetings/fake_id' => Http::response(
                (new MeetingsFakeResponse)->getMeetingFakeDetail()
            ),
        ]);

        $meetings = new Meetings;
        $meeting_detail = $meetings->meeting();

        $this->assertIsArray($meeting_detail);
        $this->assertArrayHasKey('id', $meeting_detail);
        $this->assertEquals(
            'The id field is required.',
            Arr::get($meeting_detail, 'id')[0]
        );

        $meetings = new Meetings;
        $meeting_detail = $meetings->meeting([
            'id' => 'fake_id'
        ]);

        $this->assertIsObject($meeting_detail);
        $this->assertObjectHasAttribute('id', $meeting_detail);
        $this->assertEquals('fake_id', $meeting_detail->id);

        $meetings = new Meetings;
        $meeting_detail = $meetings->meeting([
            'id' => 'fake_id'
        ], [], 'complete');

        $this->assertIsObject($meeting_detail);
        $this->assertObjectHasAttribute('id', $meeting_detail);
        $this->assertEquals('fake_id', $meeting_detail->id);

        $meetings = new Meetings;
        $meeting_detail = $meetings->meeting([
            'id' => 'fake_id'
        ], [], 'original');

        $this->assertJson($meeting_detail);
    }
}
