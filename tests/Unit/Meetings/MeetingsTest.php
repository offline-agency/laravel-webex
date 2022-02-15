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
}
