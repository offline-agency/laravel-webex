<?php

namespace Offlineagency\LaravelWebex\Tests\Fake\Meetings;

class MeetingParticipantsFakeResponse extends FakeResponse
{
    public function getMeetingParticipantsFakeList()
    {
        return json_encode((object)[
            'items' => [
                $this->fakeMeetingParticipant(),
                $this->fakeMeetingParticipant()
            ],
        ]);
    }
}
