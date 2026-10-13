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
     * @param  array<string, mixed>  $inputs
     * @param  array<string, mixed>|null  $additional_data
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

        return $this->entity($response, AttachmentActionEntity::class);
    }

    /**
     * Get a single attachment action by ID.
     *
     * @return AttachmentActionEntity|Error
     */
    public function detail(string $actionId)
    {
        $response = $this->get('attachment/actions/'.$actionId, []);

        return $this->entity($response, AttachmentActionEntity::class);
    }
}
