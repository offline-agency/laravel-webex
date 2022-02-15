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

    public function getFilteredMeetingsFakeList()
    {
        return json_encode((object)[
            'items' => [
                $this->fakeMeeting()
            ],
        ]);
    }

    public function getMeetingFakeDetail()
    {
        return json_encode(
            $this->fakeMeeting()
        );
    }
}
