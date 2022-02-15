<?php

namespace Offlineagency\LaravelWebex\Tests\Unit;

use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\TestCase;

class MeetingParticipantsTest extends TestCase
{
    public function test_meeting_participants_list()
    {
        $laravel_webex = new LaravelWebex('NDZiOWNhZDktOTczNi00NjA0LThhZTUtMWJiNWMzOTVmZTczMmQwODk2YmUtZWZm_PE93_33d69f74-a9c9-41be-80ba-7fbca5cbedc8');
        $meeting_participants_list = $laravel_webex->meeting_participants()->list('4623230fc6f44306bc790a49ac854317');

        $this->assertNull($meeting_participants_list);
    }
}
