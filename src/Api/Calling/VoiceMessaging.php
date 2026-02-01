<?php

namespace Offlineagency\LaravelWebex\Api\Calling;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Calling\VoicemailMessage as VoicemailMessageEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class VoiceMessaging extends AbstractApi
{
    /**
     * List voicemail messages for a user.
     *
     * @return VoicemailMessageEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'userId']);

        $response = $this->get('voiceMessaging/messages', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new VoicemailMessageEntity($item), $items);
    }

    /**
     * Get voicemail message detail.
     *
     * @return VoicemailMessageEntity|Error
     */
    public function detail(string $messageId)
    {
        $response = $this->get('voiceMessaging/messages/'.$messageId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new VoicemailMessageEntity($response->data);
    }
}
