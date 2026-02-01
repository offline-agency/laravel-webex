<?php

namespace Offlineagency\LaravelWebex\Api\Messages;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Messages\Messages as MessagesEntity;

class Messages extends AbstractApi
{
    /**
     * List messages in a room. Supports pagination via max/before; use listWithPagination for nextLink.
     *
     * @return MessagesEntity[]|Error
     */
    public function list(string $roomId, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, [
            'parentId', 'mentionedPeople', 'before', 'beforeMessage', 'max',
        ]);

        $response = $this->get('messages', array_merge([
            'roomId' => $roomId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($message) {
            return new MessagesEntity($message);
        }, $items);
    }

    /**
     * List messages with pagination info (nextLink from Link header).
     *
     * @return array{items: MessagesEntity[], nextLink: string|null}|Error
     */
    public function listWithPagination(string $roomId, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, [
            'parentId', 'mentionedPeople', 'before', 'beforeMessage', 'max',
        ]);

        $response = $this->getWithLink('messages', array_merge([
            'roomId' => $roomId,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return [
            'items' => array_map(function ($message) {
                return new MessagesEntity($message);
            }, $items),
            'nextLink' => $response->nextLink ?? null,
        ];
    }

    /**
     * Create a message in a room.
     *
     * @return MessagesEntity|Error
     */
    public function create(string $roomId, string $text, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, [
            'markdown', 'parentId', 'files', 'attachments', 'mentionedPeople',
        ]);

        $response = $this->post('messages', array_merge([
            'roomId' => $roomId,
            'text' => $text,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MessagesEntity($response->data);
    }

    /**
     * Get a single message by ID.
     *
     * @return MessagesEntity|Error
     */
    public function detail(string $messageId)
    {
        $response = $this->get('messages/'.$messageId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MessagesEntity($response->data);
    }

    /**
     * Edit a message.
     *
     * @return MessagesEntity|Error
     */
    public function edit(string $messageId, string $text, ?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['markdown']);

        $response = $this->put('messages/'.$messageId, array_merge([
            'text' => $text,
        ], $additional_data));

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MessagesEntity($response->data);
    }

    /**
     * Delete a message.
     *
     * @return true|Error
     */
    public function destroy(string $messageId)
    {
        $response = $this->delete('messages/'.$messageId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return true;
    }
}
