<?php

namespace Offlineagency\LaravelWebex;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Api\Meetings\Meeting;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingInvitee;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingParticipant;
use Offlineagency\LaravelWebex\Events\AuthenticationRequested;
use Offlineagency\LaravelWebex\Events\SuccessfulAuthentication;

class LaravelWebex
{
    public $base_url;

    public $httpBuilder;

    public function __construct()
    {
        $this->setBaseUrl();

        event(new AuthenticationRequested());

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

    private function setHeader()
    {
        event(new SuccessfulAuthentication());

        $this->httpBuilder = Http::withHeaders([
            'Authorization' => 'Bearer '.config('webex.bearer'),
        ]);
    }
}
