<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Webhooks as WebhooksEntity;

class Webhooks extends AbstractApi
{
    public function listWebhooks(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'ownedBy',
        ]);

        $response = $this->get('webhooks', $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        $webhooks = $response->data;

        return array_map(function ($webhook) {
            return new WebhooksEntity($webhook);
        }, $webhooks->items);
    }

    public function createWebhook(
        string $name,
        string $targetUrl,
        array $resource,
        array $event,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'filter', 'secret', 'ownedBy',
        ]);

        $response = $this->post('webhooks', array_merge([
            'name' => $name,
            'targetUrl' => $targetUrl,
            'resource' => $resource,
            'event' => $event,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new WebhooksEntity($response->data);
    }

    public function detailWebhook(
        string $webhookId
    ) {
        $response = $this->get('webhooks/'.$webhookId, []);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new WebhooksEntity($response->data);
    }

    public function updateTrackingCode(
        string $webhookId,
        string $name,
        string $targetUrl,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'secret', 'ownedBy', 'status',
        ]);

        $response = $this->put('webhooks/'.$webhookId, array_merge([
            'name' => $name,
            'targetUrl' => $targetUrl,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new WebhooksEntity($response->data);
    }

    public function destroyWebhook(
        string $webhookId
    ) {
        $response = $this->delete('webhooks/'.$webhookId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return 'Webhook deleted';
    }
}
