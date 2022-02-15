<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting as MeetingsEntity;

class Meeting extends AbstractApi
{
    public function list(
        ?string $meetingNumber = null,
        ?string $webLink = null,
        ?string $roomId = null,
        ?string $meetingType = null,
        ?string $state = null,
        ?string $participantEmail = null,
        ?bool   $current = null,
        ?string $from = null,
        ?string $to = null,
        ?int    $max = null,
        ?string $hostEmail = null,
        ?string $siteUrl = null,
        ?string $integrationTag = null
    ): ?array
    {
        $meetings = $this->get('meetings', [
            'meetingNumber' => $meetingNumber,
            'webLink' => $webLink,
            'roomId' => $roomId,
            'meetingType' => $meetingType,
            'state' => $state,
            'participantEmail' => $participantEmail,
            'current' => $current,
            'from' => $from,
            'to' => $to,
            'max' => $max,
            'hostEmail' => $hostEmail,
            'siteUrl' => $siteUrl,
            'integrationTag' => $integrationTag
        ]);

        if (is_null($meetings)) {
            return null;
        }

        return array_map(function ($meeting) {
            return new MeetingsEntity($meeting);
        }, $meetings->items);
    }

    public function detail(
        string  $meetingId,
        ?bool   $current = null,
        ?string $hostEmail = null
    ): ?MeetingsEntity
    {
        $meeting = $this->get('meetings/' . $meetingId, [
            'current' => $current,
            'hostEmail' => $hostEmail
        ]);

        if (is_null($meeting)) {
            return null;
        }

        return new MeetingsEntity($meeting);
    }

    public function create(
        string  $title,
        string  $start,
        string  $end,
        ?array $additional_data = []
    )
    {
        //TODO: implement
    }
}
