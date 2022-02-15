<?php

namespace Offlineagency\LaravelWebex;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Api\MeetingParticipants;

class LaravelWebex
{
    public $base_url = 'https://webexapis.com/v1/';

    public $httpBuilder;

    public function __construct($bearer)
    {
        $this->setHeader($bearer);
    }

    public function meeting_participants(): MeetingParticipants
    {
        return new MeetingParticipants($this);
    }

    private function setHeader($bearer)
    {
        $this->httpBuilder = Http::withHeaders([
            'Authorization' => 'Bearer ' . $bearer
        ]);
    }
}
