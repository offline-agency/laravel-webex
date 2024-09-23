<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingClosedCaptions as MeetingClosedCaptionsEntity;

class MeetingClosedCaptions extends AbstractApi
{
    public function listClosedCaptions(
        string $meetingId
    ) {
        $response = $this->get('meetingClosedCaptions', [
            'meetingId' => $meetingId,
        ]);

        if (! $response->success) {
            return new Error($response->data);
        }

        $meeting_invitees = $response->data;

        return array_map(function ($meeting_invitee) {
            return new MeetingClosedCaptionsEntity($meeting_invitee);
        }, $meeting_invitees->items);
    }

    public function listClosedCaptionSnippets(
        string $closedCaptionId,
        string $meetingId
    ) {
        $response = $this->get('meetingClosedCaptions/'.$closedCaptionId.'/snippets', [
            'meetingId' => $meetingId,
        ]);

        if (! $response->success) {
            return new Error($response->data);
        }

        $meeting_invitees = $response->data;

        return array_map(function ($meeting_invitee) {
            return new MeetingClosedCaptionsEntity($meeting_invitee);
        }, $meeting_invitees->items);
    }

    public function downloadClosedCaptionSnippets(
        string $closedCaptionId,
        string $meetingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'format'
        ]);

        $response = $this->get('meetingClosedCaptions/'.$closedCaptionId.'/download', array_merge([
            'meetingId' => $meetingId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingClosedCaptionsEntity($response->data);
    }
}
