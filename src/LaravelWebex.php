<?php

namespace Offlineagency\LaravelWebex;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Api\Meetings\Meeting;
use Offlineagency\LaravelWebex\Api\Meetings\MeetingParticipant;

class LaravelWebex
{
    public $base_url = 'https://webexapis.com/v1/';

    public $httpBuilder;

    public function __construct($bearer)
    {
        $this->setHeader($bearer);
    }

    public function meeting_participants(): MeetingParticipant
    {
        return new MeetingParticipant($this);
    }

    public function meeting(): Meeting
    {
        return new Meeting($this);
    }

    private function setHeader($bearer)
    {
        $this->httpBuilder = Http::withHeaders([
            'Authorization' => 'Bearer ' . $bearer
        ]);
    }
}
