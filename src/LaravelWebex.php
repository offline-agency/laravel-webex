<?php

namespace Offlineagency\LaravelWebex;

use Offlineagency\LaravelWebex\Api\Meetings\Meeting;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingInvitee;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingParticipant;
use Offlineagency\LaravelWebex\Events\AuthenticationRequested;
use Offlineagency\LaravelWebex\Events\SuccessfulAuthentication;

class LaravelWebex
{
    public $base_url;

    public $httpBuilder;

    private $bearer;

    public function __construct()
    {
        $this->setBaseUrl();

        event(new AuthenticationRequested());

        $this->auth();

        $this->setHeader();
    }

    public function meeting_participants(): MeetingParticipant
    {
        return new MeetingParticipant($this);
    }

    public function meeting(): Meeting
    {
        return new Meeting($this);
    }

    public function meeting_invitees(): MeetingInvitee
    {
        return new MeetingInvitee($this);
    }

    private function setBaseUrl()
    {
        $this->base_url = config('webex.base_url');
    }

    private function auth()
    {
        $this->setBearer('fake_bearer');
    }

    private function setHeader()
    {
        event(new SuccessfulAuthentication());

        $this->httpBuilder = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->bearer
        ]);
    }

    private function setBearer($bearer)
    {
        $this->bearer = $bearer;
    }
}
