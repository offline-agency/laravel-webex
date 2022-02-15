<?php

namespace Offlineagency\LaravelWebex\Tests\Unit;

use Offlineagency\LaravelWebex\Client;
use Offlineagency\LaravelWebex\Tests\TestCase;

class MeetingParticipantsTest extends TestCase
{
    public function test_meeting_participants_list()
    {
        $client = new Client('fake_bearer');
        $meeting_participants_list = $client->meeting_participants()->list('fake_meeting_id');

        dd($meeting_participants_list);
    }
}
