<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingChats as MeetingChatsEntity;

class MeetingChats extends AbstractApi
{
    public function listChats(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'offset'
        ]);

        $response = $this->get('meetings/postMeetingChats', array_merge([
            'meetingId' => $meetingId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meeting) {
            return new MeetingChatsEntity($meeting);
        }, $items);
    }

    public function destroyChats(
        string $meeting_id
    ) {

        $response = $this->delete('meetings/postMeetingChats/'.$meeting_id, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Meeting chats deleted';
    }
}
