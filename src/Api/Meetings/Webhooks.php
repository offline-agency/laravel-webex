<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Webhooks as WebhooksEntity;

class Webhooks extends AbstractApi
{
    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return WebhooksEntity[]|Error
     */
    public function listWebhooks(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'max', 'ownedBy',
        ]);

        $response = $this->get('webhooks', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($webhook) {
            return new WebhooksEntity($webhook);
        }, $items);
    }

    /**
     * @param  array<mixed>  $resource
     * @param  array<mixed>  $event
     * @param  array<string, mixed>|null  $additional_data
     * @return WebhooksEntity|Error
     */
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

        return $this->entity($response, WebhooksEntity::class);
    }

    /**
     * @return WebhooksEntity|Error
     */
    public function detailWebhook(
        string $webhookId
    ) {
        $response = $this->get('webhooks/'.$webhookId, []);

        return $this->entity($response, WebhooksEntity::class);
    }

    /**
     * Update a webhook (name, targetUrl, secret, status).
     *
     * @param  array<string, mixed>|null  $additional_data
     * @return WebhooksEntity|Error
     */
    public function updateWebhook(
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

        return $this->entity($response, WebhooksEntity::class);
    }

    /**
     * @deprecated Use updateWebhook() instead. This method name was misleading; it updates the webhook, not a tracking code.
     *
     * @param  array<string, mixed>|null  $additional_data
     * @return WebhooksEntity|Error
     */
    public function updateTrackingCode(
        string $webhookId,
        string $name,
        string $targetUrl,
        ?array $additional_data = []
    ) {
        return $this->updateWebhook($webhookId, $name, $targetUrl, $additional_data);
    }

    /**
     * @return string|Error
     */
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
