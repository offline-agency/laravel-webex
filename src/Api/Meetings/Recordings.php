<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Recordings as RecordingsEntity;

class Recordings extends AbstractApi
{
    public function listRecordings(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'from', 'to', 'meetingId', 'hostEmail', 'siteUrl', 'integrationTag', 'topic', 'format', 'serviceType', 'status',
        ]);

        $response = $this->get('recordings', $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        $recordings = $response->data;

        return array_map(function ($recording) {
            return new RecordingsEntity($recording);
        }, $recordings->items);
    }

    public function listRecordingsForAnAdminOrComplianceOfficer(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'from', 'to', 'meetingId', 'hostEmail', 'siteUrl', 'integrationTag', 'topic', 'format', 'serviceType', 'status',
        ]);

        $response = $this->get('admin/recordings', $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        $recordings = $response->data;

        return array_map(function ($recording) {
            return new RecordingsEntity($recording);
        }, $recordings->items);
    }

    public function detailRecording(
        string $recordingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail',
        ]);

        $response = $this->get('recordings/'.$recordingId, $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new RecordingsEntity($response->data);
    }

    public function destroyRecording(
        string $recordingId,
        ?array $additional_data = []
    ) {
        $additional_data  = $this->data($additional_data, [
            'reason', 'comment',
        ]);

        $response = $this->delete('recordings/'.$recordingId, $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        return 'Recording deleted';
    }

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

        if (! $response->success) {
            return new Error($response->data);
        }

        return new RecordingsEntity($response->data);
    }

    public function restoreRecordingsFromRecycleBin(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'restoreAll', 'recordingIds','siteUrl',
        ]);

        $response = $this->post('recordings/restore', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new RecordingsEntity($response->data);
    }

    public function purgeRecordingsFromRecycleBin(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'purgeAll', 'recordingIds','siteUrl',
        ]);

        $response = $this->post('recordings/purge', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new RecordingsEntity($response->data);
    }
}
