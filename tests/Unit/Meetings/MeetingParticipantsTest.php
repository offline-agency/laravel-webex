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

        $single_meeting_participant = null;
        foreach ($meeting_participants_list as $meeting_participant) {
            $this->assertInstanceOf(MeetingParticipant::class, $meeting_participant);
            $single_meeting_participant = $meeting_participant;
        }

        $this->assertEquals('fake_id', $single_meeting_participant->id);
    }
}
