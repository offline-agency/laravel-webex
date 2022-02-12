<?php

namespace Offlineagency\LaravelWebex\Tests\Unit;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Rooms;
use Offlineagency\LaravelWebex\Tests\Fake\RoomsFakeResponse;
use Offlineagency\LaravelWebex\Tests\TestCase;

class RoomsTest extends TestCase
{
    public function test_rooms_list()
    {
        Http::fake([
            'rooms' => Http::response(
                (new RoomsFakeResponse)->getRoomsFakeList()
            ),
        ]);

        $rooms = new Rooms;
        $rooms_list = $rooms->rooms();

        $this->assertCount(1, $rooms_list);

        $room = $rooms_list[0];
        $this->assertIsObject($room);
        $this->assertObjectHasAttribute('id', $room);
        $this->assertEquals('fake_id', $room->id);

        $rooms = new Rooms;
        $rooms_list = $rooms->rooms([], 'complete');

        $this->assertIsObject($rooms_list);
        $this->assertObjectHasAttribute('items', $rooms_list);

        $rooms = new Rooms;
        $rooms_list = $rooms->rooms([], 'original');

        $this->assertJson($rooms_list);
    }
}
