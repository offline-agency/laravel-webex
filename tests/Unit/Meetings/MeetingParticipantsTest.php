<?php

namespace Offlineagency\LaravelWebex\Tests\Unit\Meetings;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingParticipant;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\Fake\Meetings\MeetingParticipantsFakeResponse;
use Offlineagency\LaravelWebex\Tests\TestCase;

class MeetingParticipantsTest extends TestCase
{
    public function test_meeting_participants_list()
    {
        Http::fake([
            'meetingParticipants?meetingId=fake_id' => Http::response(
                (new MeetingParticipantsFakeResponse())->getMeetingParticipantsFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex('fake_bearer');
        $meeting_participants_list = $laravel_webex->meeting_participants()->list('fake_id');

        $this->assertCount(2, $meeting_participants_list);

        $first_meeting_participant = $meeting_participants_list[0];
        $this->assertInstanceOf(MeetingParticipant::class, $first_meeting_participant);
        $this->assertEquals('fake_id', $first_meeting_participant->id);;
    }
}
