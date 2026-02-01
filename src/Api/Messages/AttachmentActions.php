<?php

namespace Offlineagency\LaravelWebex\Api\Messages;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\AttachmentAction as AttachmentActionEntity;

class AttachmentActions extends AbstractApi
{
    /**
     * Create an attachment action (e.g. when a user submits a card).
     *
     * @return AttachmentActionEntity|Error
     */
    public function create(string $type, string $messageId, array $inputs, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, []);

        $response = $this->post('attachment/actions', array_merge([
            'type' => $type,
            'messageId' => $messageId,
            'inputs' => $inputs,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new AttachmentActionEntity($response->data);
    }

    /**
     * Get a single attachment action by ID.
     *
     * @return AttachmentActionEntity|Error
     */
    public function detail(string $actionId)
    {
        $response = $this->get('attachment/actions/'.$actionId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new AttachmentActionEntity($response->data);
    }
}
