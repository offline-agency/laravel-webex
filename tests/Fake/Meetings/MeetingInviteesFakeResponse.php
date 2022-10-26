<?php

namespace Offlineagency\LaravelWebex\Tests\Fake\Meetings;

class MeetingInviteesFakeResponse extends FakeResponse
{
    public function getMeetingInviteesFakeList()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeetingInvitee(),
                $this->fakeMeetingInvitee(),
            ],
        ]);
    }

    public function getMeetingInviteesFakeDetail()
    {
        return json_encode(
            $this->fakeMeetingInvitee()
        );
    }

    public function getNewMeetingInviteeFakeDetail()
    {
        return json_encode(
            $this->fakeMeetingInvitee()
        );
    }

    public function getNewMeetingInviteesFakeList()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMeetingInvitee(),
                $this->fakeMeetingInvitee(),
            ],
        ]);
    }

    public function getUpdatedMeetingInviteeFakeDetail()
    {
        return json_encode(
            $this->fakeMeetingInvitee()
        );
    }

    public function getDeleteMeetingInviteeFakeResponse()
    {
        return null;
    }
}
