<?php

namespace Offlineagency\LaravelWebex\Tests\Fake;

class RoomsFakeResponse
{
    public function getRoomsFakeList()
    {
        return json_encode((object)[
            'items' => [
                (object)[
                    'id' => 'fake_id',
                    'title' => 'fake_title',
                    'type' => 'group',
                    'isLocked' => false,
                    'lastActivity' => '2022-02-11T09:15:17.365Z',
                    'creatorId' => 'fake_creator_id',
                    'created' => '2022-02-11T09:15:16.513Z',
                    'ownerId' => 'fake_owner_id'
                ]
            ]
        ]);
    }
}
