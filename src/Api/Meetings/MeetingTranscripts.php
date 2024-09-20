<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingTranscripts as MeetingTranscriptsEntity;

class MeetingTranscripts extends AbstractApi
{
    public function listTranscripts(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'meetingId', 'hostEmail', 'siteUrl', 'from', 'to', 'max',
        ]);

        $response = $this->get('meetingTranscripts', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetingTranscripts = $response->data;

        return array_map(function ($meetingTranscript) {
            return new MeetingTranscriptsEntity($meetingTranscript);
        }, $meetingTranscripts->items);
    }

    public function listTranscriptsForComplianceOfficer(
        string $siteUrl,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'from', 'to', 'max',
        ]);

        $response = $this->get('admin/meetingTranscripts', array_merge([
            'siteUrl' => $siteUrl,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetingTranscripts = $response->data;

        return array_map(function ($meetingTranscript) {
            return new MeetingTranscriptsEntity($meetingTranscript);
        }, $meetingTranscripts->items);
    }

    public function downloadTranscript(
        string $transcriptId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'format', 'hostEmail',
        ]);

        $response = $this->get('meetingTranscripts/'.$transcriptId.'/download', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingTranscriptsEntity($response->data);
    }

    public function listSnippetsOfTranscript(
        string $transcriptId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max',
        ]);

        $response = $this->get('meetingTranscripts'.$transcriptId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $meetingTranscripts = $response->data;

        return array_map(function ($meetingTranscript) {
            return new MeetingTranscriptsEntity($meetingTranscript);
        }, $meetingTranscripts->items);
    }

    public function detailTranscriptSnippet(
        string $transcriptId,
        string $snippetId
    ) {

        $response = $this->get('meetingTranscripts/'.$transcriptId.'/snippets/'.$snippetId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingTranscriptsEntity($response->data);
    }

    public function updateTranscriptSnippet(
        string $transcriptId,
        string $snippetId,
        string $text,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'reason',
        ]);

        $response = $this->put('meetingTranscripts/'.$transcriptId.'/snippets/'.$snippetId, array_merge([
            'text' => $text,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingTranscriptsEntity($response->data);
    }

    public function destroyTranscript(
        string $transcriptId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'reason', 'comment',
        ]);

        $response = $this->delete('meetingTranscripts/'.$transcriptId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Transcript deleted';
    }
}
