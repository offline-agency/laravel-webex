<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingPreferences as MeetingPreferencesEntity;

class MeetingPreferences extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingPreferencesEntity|Error
     */
    public function detailPreference(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'userEmail', 'siteUrl',
        ]);

        $response = $this->get('meetingPreferences', $additional_data);

        return $this->entity($response, MeetingPreferencesEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingPreferencesEntity|Error
     */
    public function detailPersonalRoomOptions(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'userEmail', 'siteUrl',
        ]);

        $response = $this->get('meetingPreferences/personalMeetingRoom', $additional_data);

        return $this->entity($response, MeetingPreferencesEntity::class);
    }

    /**
     * @param  array<mixed>  $coHost
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingPreferencesEntity|Error
     */
    public function updatePersonalRoomOptions(
        string $topic,
        string $hostPin,
        bool $enabledAutoLock,
        int $autoLockMinutes,
        bool $enabledNotifyHost,
        bool $supportCoHost,
        array $coHost,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'userEmail', 'siteUrl', 'supportAnyoneAsCoHost', 'allowFirstUserToBeCoHost', 'allowAuthenticatedDevices',
        ]);

        $response = $this->put('meetingPreferences/personalMeetingRoom/', array_merge([
            'topic' => $topic,
            'hostPin' => $hostPin,
            'enabledAutoLock' => $enabledAutoLock,
            'autoLockMinutes' => $autoLockMinutes,
            'enabledNotifyHost' => $enabledNotifyHost,
            'supportCoHost' => $supportCoHost,
            'coHost' => $coHost,
        ], $additional_data));

        return $this->entity($response, MeetingPreferencesEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingPreferencesEntity|Error
     */
    public function detailAudioOptions(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'userEmail', 'siteUrl',
        ]);

        $response = $this->get('meetingPreferences/audio', $additional_data);

        return $this->entity($response, MeetingPreferencesEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingPreferencesEntity|Error
     */
    public function detailVideoOptions(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'userEmail', 'siteUrl',
        ]);

        $response = $this->get('meetingPreferences/video', $additional_data);

        return $this->entity($response, MeetingPreferencesEntity::class);
    }

    /**
     * @param  array<mixed>  $videoDevices
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingPreferencesEntity|Error
     */
    public function updateVideoOptions(
        array $videoDevices,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'userEmail', 'siteUrl',
        ]);

        $response = $this->put('meetingPreferences/video/', array_merge([
            'videoDevices' => $videoDevices,
        ], $additional_data));

        return $this->entity($response, MeetingPreferencesEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingPreferencesEntity|Error
     */
    public function detailSchedulingOptions(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'userEmail', 'siteUrl',
        ]);

        $response = $this->get('meetingPreferences/schedulingOptions', $additional_data);

        return $this->entity($response, MeetingPreferencesEntity::class);
    }

    /**
     * @param  array<mixed>  $videoDevices
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingPreferencesEntity|Error
     */
    public function updateSchedulingOptions(
        array $videoDevices,
        bool $enabledJoinBeforeHost,
        int $joinBeforeHostMinutes,
        bool $enabledAutoShareRecording,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'userEmail', 'siteUrl', 'enabledWebexAssistantByDefault', 'delegateEmails',
        ]);

        $response = $this->put('meetingPreferences/schedulingOptions/', array_merge([
            'videoDevices' => $videoDevices,
            'enabledJoinBeforeHost' => $enabledJoinBeforeHost,
            'joinBeforeHostMinutes' => $joinBeforeHostMinutes,
            'enabledAutoShareRecording' => $enabledAutoShareRecording,
        ], $additional_data));

        return $this->entity($response, MeetingPreferencesEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingPreferencesEntity|Error
     */
    public function insertDelegateEmails(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'userEmail', 'siteUrl', 'emails',
        ]);

        $response = $this->post('meetingPreferences/schedulingOptions/delegateEmails/insert/', $additional_data);

        return $this->entity($response, MeetingPreferencesEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingPreferencesEntity|Error
     */
    public function deleteDelegateEmails(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'userEmail', 'siteUrl', 'emails',
        ]);

        $response = $this->post('meetingPreferences/schedulingOptions/delegateEmails/delete/', $additional_data);

        return $this->entity($response, MeetingPreferencesEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingPreferencesEntity|Error
     */
    public function detailSiteList(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'userEmail', 'siteUrl',
        ]);

        $response = $this->get('meetingPreferences/sites', $additional_data);

        return $this->entity($response, MeetingPreferencesEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingPreferencesEntity|Error
     */
    public function updateDefaultSite(
        bool $defaultSite,
        string $siteUrl,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'userEmail',
        ]);

        $response = $this->put('meetingPreferences/sites/', array_merge([
            'defaultSite' => $defaultSite,
            'siteUrl' => $siteUrl,
        ], $additional_data));

        return $this->entity($response, MeetingPreferencesEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return MeetingPreferencesEntity|Error
     */
    public function batchRefreshPersonalMeetingRoomID(
        string $siteUrl,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'personalMeetingRoomIds',
        ]);

        $response = $this->post('admin/meetingPreferences/personalMeetingRoom/refreshId', array_merge([
            'siteUrl' => $siteUrl,
        ], $additional_data));

        return $this->entity($response, MeetingPreferencesEntity::class);
    }
}
