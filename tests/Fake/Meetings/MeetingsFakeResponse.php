<?php

namespace Offlineagency\LaravelWebex\Tests\Fake\Meetings;

class MeetingsFakeResponse extends FakeResponse
{
    public function getMeetingsFakeList()
    {
        return json_encode((object)[
            'items' => [
                $this->fakeMeeting(),
                $this->fakeMeeting()
            ],
        ]);
    }
}
