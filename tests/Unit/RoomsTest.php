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
        $rooms_list = $rooms->getRoomsList();

        $this->assertCount(1, $rooms_list);

        $room = $rooms_list[0];
        $this->assertEquals('fake_id', $room->id);
        $this->assertEquals('fake_title', $room->title);
        $this->assertEquals('group', $room->type);
        $this->assertFalse($room->isLocked);
        $this->assertEquals('2022-02-11T09:15:17.365Z', $room->lastActivity);
        $this->assertEquals('fake_creator_id', $room->creatorId);
        $this->assertEquals('2022-02-11T09:15:16.513Z', $room->created);
        $this->assertEquals('fake_owner_id', $room->ownerId);

        $rooms = new Rooms;
        $rooms_list = $rooms->getRoomsList([], 'complete');

        $this->assertIsObject($rooms_list);
        $this->assertObjectHasAttribute('items', $rooms_list);

        $rooms = new Rooms;
        $rooms_list = $rooms->getRoomsList([], 'original');

        $this->assertJson($rooms_list);
    }
}
