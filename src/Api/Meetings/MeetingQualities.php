<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingQualities as MeetingQualitiesEntity;

class MeetingQualities extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingQualitiesEntity|Error
     */
    public function detailQualities(
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'offset',
        ]);

        $response = $this->get('meeting/qualities', array_merge([
            'meetingId' => $meetingId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingQualitiesEntity($response->data);
    }
}
