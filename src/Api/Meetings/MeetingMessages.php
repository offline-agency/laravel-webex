<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;

class MeetingMessages extends AbstractApi
{

    public function destroyMessage(
        string $meetingMessageId
    ) {
        $response = $this->delete('meeting/messages/'.$meetingMessageId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Meeting Message deleted';
    }
}
