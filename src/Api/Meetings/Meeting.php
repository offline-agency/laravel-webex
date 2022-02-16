<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting as MeetingsEntity;

class Meeting extends AbstractApi
{
    public function list(
        ?array $additional_data = []
    ): ?array
    {
        $meetings = $this->get('meetings', [
            'meetingNumber' => $this->value($additional_data, 'meetingNumber'),
            'webLink' => $this->value($additional_data, 'webLink'),
            'roomId' => $this->value($additional_data, 'roomId'),
            'meetingType' => $this->value($additional_data, 'meetingType'),
            'state' => $this->value($additional_data, 'state'),
            'participantEmail' => $this->value($additional_data, 'participantEmail'),
            'current' => $this->value($additional_data, 'current'),
            'from' => $this->value($additional_data, 'from'),
            'to' => $this->value($additional_data, 'to'),
            'max' => $this->value($additional_data, 'max'),
            'hostEmail' => $this->value($additional_data, 'hostEmail'),
            'siteUrl' => $this->value($additional_data, 'siteUrl'),
            'integrationTag' => $this->value($additional_data, 'integrationTag')
        ]);

        if (is_null($meetings)) {
            return null;
        }

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $meetings->items);
    }

    public function detail(
        string $meetingId,
        ?array $additional_data = []
    ): ?MeetingsEntity
    {
        $meeting = $this->get('meetings/' . $meetingId, [
            'current' => $this->value($additional_data, 'current'),
            'hostEmail' => $this->value($additional_data, 'hostEmail')
        ]);

        if (is_null($meeting)) {
            return null;
        }

        return new MeetingsEntity($meeting);
    }

    public function create(
        string $title,
        string $start,
        string $end,
        ?array $additional_data = []
    )
    {
        //TODO: implement
    }
}
