<?php

namespace Offlineagency\LaravelWebex\Tests\Unit;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Meetings;
use Offlineagency\LaravelWebex\Tests\Fake\MeetingsFakeResponse;
use Offlineagency\LaravelWebex\Tests\TestCase;

class MeetingsTest extends TestCase
{
    public function test_rooms_list()
    {
        Http::fake([
            'meetings' => Http::response(
                (new MeetingsFakeResponse)->getMeetingsFakeList()
            ),
        ]);

        $meetings = new Meetings;
        $meetings_list = $meetings->getMeetingsList();

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
        $meetings_list = $meetings->getMeetingsList([], 'complete');

        $this->assertIsObject($meetings_list);
        $this->assertObjectHasAttribute('items', $meetings_list);

        $meetings = new Meetings;
        $meetings_list = $meetings->getMeetingsList([], 'original');

        $this->assertJson($meetings_list);
    }
}
