<?php

namespace Offlineagency\LaravelWebex\Tests\Fake\Meetings;

class MeetingParticipantsFakeResponse extends FakeResponse
{
    public function getMeetingParticipantsFakeList()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeetingParticipant(),
                $this->fakeMeetingParticipant(),
            ],
        ]);
    }

    public function getMeetingParticipantsFakeDetail()
    {
        return json_encode(
            $this->fakeMeetingParticipant(),
        );
    }

    public function getErrorOnMeetingsFakeList()
    {
        return json_encode(
            $this->fakeError()
        );
    }
}
