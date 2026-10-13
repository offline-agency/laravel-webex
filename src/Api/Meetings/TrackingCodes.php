<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\TrackingCodes as TrackingCodesEntity;

class TrackingCodes extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return TrackingCodesEntity[]|Error
     */
    public function listTrackingCodes(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'siteUrl',
        ]);

        $response = $this->get('admin/meeting/config/trackingCodes/', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($TrackingCode) {
            return new TrackingCodesEntity($TrackingCode);
        }, $items);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return TrackingCodesEntity|Error
     */
    public function detailTrackingCode(
        string $trackingCodeId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'siteUrl',
        ]);

        $response = $this->get('admin/meeting/config/trackingCodes/'.$trackingCodeId, $additional_data);

        return $this->entity($response, TrackingCodesEntity::class);
    }

    /**
     * @param  array<mixed>  $options
     * @param  array<mixed>  $inputMode
     * @param  array<mixed>  $scheduleStartCodes
     * @return TrackingCodesEntity|Error
     */
    public function createTrackingCode(
        string $name,
        string $siteUrl,
        array $options,
        array $inputMode,
        string $hostProfileCode,
        array $scheduleStartCodes
    ) {
        $response = $this->post('admin/meeting/config/trackingCodes', [
            'name' => $name,
            'siteUrl' => $siteUrl,
            'options' => $options,
            'inputMode' => $inputMode,
            'hostProfileCode' => $hostProfileCode,
            'scheduleStartCodes' => $scheduleStartCodes,
        ]);

        return $this->entity($response, TrackingCodesEntity::class);
    }

    /**
     * @param  array<mixed>  $options
     * @param  array<mixed>  $inputMode
     * @param  array<mixed>  $scheduleStartCodes
     * @return TrackingCodesEntity|Error
     */
    public function updateTrackingCode(
        string $trackingCodeId,
        string $name,
        string $siteUrl,
        array $options,
        array $inputMode,
        string $hostProfileCode,
        array $scheduleStartCodes
    ) {
        $response = $this->put('admin/meeting/config/trackingCodes/'.$trackingCodeId, [
            'name' => $name,
            'siteUrl' => $siteUrl,
            'options' => $options,
            'inputMode' => $inputMode,
            'hostProfileCode' => $hostProfileCode,
            'scheduleStartCodes' => $scheduleStartCodes,
        ]);

        return $this->entity($response, TrackingCodesEntity::class);
    }

    /**
     * @return string|Error
     */
    public function destroyTrackingCode(
        string $trackingCodeId,
        string $siteUrl
    ) {
        $response = $this->delete('admin/meeting/config/trackingCodes/'.$trackingCodeId, [
            'siteUrl' => $siteUrl,
        ]);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Tracking code deleted';
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return TrackingCodesEntity|Error
     */
    public function detailUserTrackingCodes(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'siteUrl', 'personId',
        ]);

        $response = $this->get('admin/meeting/userconfig/trackingCodes', $additional_data);

        return $this->entity($response, TrackingCodesEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return TrackingCodesEntity|Error
     */
    public function updateUserTrackingCodes(
        string $siteUrl,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'personId', 'email', 'trackingCodes',
        ]);

        $response = $this->put('admin/meeting/userconfig/trackingCodes', array_merge([
            'siteUrl' => $siteUrl,
        ], $additional_data));

        return $this->entity($response, TrackingCodesEntity::class);
    }
}
