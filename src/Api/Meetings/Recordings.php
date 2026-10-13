<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Recordings as RecordingsEntity;

class Recordings extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return RecordingsEntity[]|Error
     */
    public function listRecordings(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'from', 'to', 'meetingId', 'hostEmail', 'siteUrl', 'integrationTag', 'topic', 'format', 'serviceType', 'status',
        ]);

        $response = $this->get('recordings', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($recording) {
            return new RecordingsEntity($recording);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return RecordingsEntity[]|Error
     */
    public function listRecordingsForAnAdminOrComplianceOfficer(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'from', 'to', 'meetingId', 'hostEmail', 'siteUrl', 'integrationTag', 'topic', 'format', 'serviceType', 'status',
        ]);

        $response = $this->get('admin/recordings', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($recording) {
            return new RecordingsEntity($recording);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return RecordingsEntity|Error
     */
    public function detailRecording(
        string $recordingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail',
        ]);

        $response = $this->get('recordings/'.$recordingId, $additional_data);

        return $this->entity($response, RecordingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return string|Error
     */
    public function destroyRecording(
        string $recordingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'reason', 'comment',
        ]);

        $response = $this->delete('recordings/'.$recordingId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Recording deleted';
    }

    /**
     * @param  list<string>  $recordingIds
     * @param  array<string, mixed>|null  $additional_data
     * @return RecordingsEntity|Error
     */
    public function moveRecordingsIntoRecycleBin(
        array $recordingIds,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'siteUrl',
        ]);

        $response = $this->post('recordings/softDelete', array_merge([
            'recordingIds' => $recordingIds,
        ], $additional_data));

        return $this->entity($response, RecordingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return RecordingsEntity|Error
     */
    public function restoreRecordingsFromRecycleBin(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'restoreAll', 'recordingIds', 'siteUrl',
        ]);

        $response = $this->post('recordings/restore', $additional_data);

        return $this->entity($response, RecordingsEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return RecordingsEntity|Error
     */
    public function purgeRecordingsFromRecycleBin(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'purgeAll', 'recordingIds', 'siteUrl',
        ]);

        $response = $this->post('recordings/purge', $additional_data);

        return $this->entity($response, RecordingsEntity::class);
    }
}
