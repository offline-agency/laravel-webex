<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\MeetingInvitee as MeetingInviteeEntity;

class MeetingInvitee extends AbstractApi
{
    public function list(
        string $meetingId,
        ?array $additional_data = []
    )
    {
        $additional_data = $this->data($additional_data, [
            'max', 'hostEmail'
        ]);

        $response = $this->get('meetingInvitees', array_merge([
            'meetingId' => $meetingId
        ], $additional_data));

        if (!$response->success) {
            return new Error($response->data);
        }

        $meeting_invitees = $response->data;

        return array_map(function ($meeting_invitee) {
            return new MeetingInviteeEntity($meeting_invitee);
        }, $meeting_invitees->items);
    }

    public function detail(
        string $meetingInviteeId,
        ?array $additional_data = []
    )
    {
        $additional_data = $this->data($additional_data, [
            'hostEmail'
        ]);

        $response = $this->get('meetingInvitees/' . $meetingInviteeId, $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new MeetingInviteeEntity($response->data);
    }

    public function create(
        string $meetingId,
        string $email,
        ?array $additional_data = []
    )
    {
        $additional_data = $this->data($additional_data, [
            'displayName', 'coHost', 'hostEmail', 'sendEmail', 'panelist'
        ]);

        $response = $this->post('meetingInvitees', array_merge([
            'meetingId' => $meetingId,
            'email' => $email
        ], $additional_data));

        if (!$response->success) {
            return new Error($response->data);
        }

        return new MeetingInviteeEntity($response->data);
    }

    public function bulk_create(
        string $meetingId,
        ?array $additional_data = []
    )
    {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'items'
        ]);

        $response = $this->post('meetingInvitees/bulkInsert', array_merge([
            'meetingId' => $meetingId
        ], $additional_data));

        if (!$response->success) {
            return new Error($response->data);
        }

        $meeting_invitees = $response->data;

        return array_map(function ($meeting_invitee) {
            return new MeetingInviteeEntity($meeting_invitee);
        }, $meeting_invitees->items);

    }

    public function update(
        string $meetingInviteeId,
        string $email,
        ?array $additional_data = []
    )
    {
        $additional_data = $this->data($additional_data, [
            'displayName', 'coHost', 'hostEmail', 'sendEmail', 'panelist'
        ]);

        $response = $this->post('meetingInvitees/' . $meetingInviteeId, array_merge([
            'email' => $email
        ], $additional_data));

        if (!$response->success) {
            return new Error($response->data);
        }

        return new MeetingInviteeEntity($response->data);
    }

    public function destroy(
        string $meetingInviteeId,
        ?array $additional_data = []
    )
    {
        $additional_data = $this->data($additional_data, [
            'hostEmail', 'sendEmail'
        ]);

        $response = $this->delete('meetingInvitees/' . $meetingInviteeId, $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        return 'Meeting invitee deleted';
    }
}
