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

    public function getNewMeetingFakeDetail()
    {
        return json_encode(
            $this->fakeMeeting([
                'agenda' => 'fake_created_agenda',
                'enabledAutoRecordMeeting' => true
            ])
        );
    }

    public function getUpdatedMeetingFakeDetail()
    {
        return json_encode(
            $this->fakeMeeting([
                'agenda' => 'fake_updated_agenda',
                'enabledAutoRecordMeeting' => false
            ])
        );
    }

    public function getDeleteMeetingFakeResponse()
    {
        return null;
    }
}
