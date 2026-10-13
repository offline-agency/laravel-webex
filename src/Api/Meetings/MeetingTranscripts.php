<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingTranscripts as MeetingTranscriptsEntity;

class MeetingTranscripts extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingTranscriptsEntity[]|Error
     */
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

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meetingTranscript) {
            return new MeetingTranscriptsEntity($meetingTranscript);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingTranscriptsEntity[]|Error
     */
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

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meetingTranscript) {
            return new MeetingTranscriptsEntity($meetingTranscript);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingTranscriptsEntity|Error
     */
    public function downloadTranscript(
        string $transcriptId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'format', 'hostEmail',
        ]);

        $response = $this->get('meetingTranscripts/'.$transcriptId.'/download', $additional_data);

        return $this->entity($response, MeetingTranscriptsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingTranscriptsEntity[]|Error
     */
    public function listSnippetsOfTranscript(
        string $transcriptId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max',
        ]);

        $response = $this->get('meetingTranscripts/'.$transcriptId.'/snippets', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($meetingTranscript) {
            return new MeetingTranscriptsEntity($meetingTranscript);
        }, $items);
    }

    /**
     * @return MeetingTranscriptsEntity|Error
     */
    public function detailTranscriptSnippet(
        string $transcriptId,
        string $snippetId
    ) {

        $response = $this->get('meetingTranscripts/'.$transcriptId.'/snippets/'.$snippetId, []);

        return $this->entity($response, MeetingTranscriptsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingTranscriptsEntity|Error
     */
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

        return $this->entity($response, MeetingTranscriptsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return string|Error
     */
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
