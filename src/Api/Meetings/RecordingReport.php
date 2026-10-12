<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\RecordingReport as RecordingReportEntity;

class RecordingReport extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return RecordingReportEntity[]|Error
     */
    public function listRecordingAuditReportSummaries(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'from', 'to', 'hostEmail', 'siteUrl',
        ]);

        $response = $this->get('recordingReport/accessSummary', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($recordingReport) {
            return new RecordingReportEntity($recordingReport);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return RecordingReportEntity|Error
     */
    public function detailRecordAuditReport(
        string $recordingId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'max',
        ]);

        $response = $this->get('recordingReport/accessDetail', array_merge([
            'recordingId' => $recordingId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new RecordingReportEntity($response->data);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return RecordingReportEntity[]|Error
     */
    public function listArchiveSummaries(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'from', 'to', 'siteUrl',
        ]);

        $response = $this->get('recordingReport/meetingArchiveSummaries', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($recordingReport) {
            return new RecordingReportEntity($recordingReport);
        }, $items);
    }

    /**
     * @return RecordingReportEntity|Error
     */
    public function detailArchive(
        string $archiveId
    ) {
        $response = $this->get('recordingReport/meetingArchives/'.$archiveId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new RecordingReportEntity($response->data);
    }
}
