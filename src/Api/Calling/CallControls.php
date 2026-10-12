<?php

namespace Offlineagency\LaravelWebex\Api\Calling;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Calling\Call as CallEntity;
use Offlineagency\LaravelWebex\Entities\Calling\CallHistoryItem as CallHistoryItemEntity;
use Offlineagency\LaravelWebex\Entities\Error;

/**
 * @phpstan-import-type ApiResponse from AbstractApi
 */
class CallControls extends AbstractApi
{
    /**
     * Dial (initiate outbound call).
     *
     * @param  array<string, mixed>  $body
     * @return CallEntity|Error
     */
    public function dial(array $body)
    {
        $response = $this->post('telephony/calls/dial', $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new CallEntity($response->data);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function answer(array $body)
    {
        return $this->post('telephony/calls/answer', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function reject(array $body)
    {
        return $this->post('telephony/calls/reject', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function hangup(array $body)
    {
        return $this->post('telephony/calls/hangup', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function hold(array $body)
    {
        return $this->post('telephony/calls/hold', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function resume(array $body)
    {
        return $this->post('telephony/calls/resume', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function mute(array $body)
    {
        return $this->post('telephony/calls/mute', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function unmute(array $body)
    {
        return $this->post('telephony/calls/unmute', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function divert(array $body)
    {
        return $this->post('telephony/calls/divert', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function transfer(array $body)
    {
        return $this->post('telephony/calls/transfer', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function park(array $body)
    {
        return $this->post('telephony/calls/park', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function retrieve(array $body)
    {
        return $this->post('telephony/calls/retrieve', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function startRecording(array $body)
    {
        return $this->post('telephony/calls/startRecording', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function stopRecording(array $body)
    {
        return $this->post('telephony/calls/stopRecording', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function pauseRecording(array $body)
    {
        return $this->post('telephony/calls/pauseRecording', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function resumeRecording(array $body)
    {
        return $this->post('telephony/calls/resumeRecording', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function transmitDtmf(array $body)
    {
        return $this->post('telephony/calls/transmitDtmf', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function push(array $body)
    {
        return $this->post('telephony/calls/push', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function pickup(array $body)
    {
        return $this->post('telephony/calls/pickup', $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return ApiResponse
     */
    public function bargeIn(array $body)
    {
        return $this->post('telephony/calls/bargeIn', $body);
    }

    /**
     * List active calls.
     *
     * @param  array<string, mixed>|null  $additional_data
     * @return CallEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, []);

        $response = $this->get('telephony/calls', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new CallEntity($item), $items);
    }

    /**
     * Get call details.
     *
     * @return CallEntity|Error
     */
    public function detail(string $callId)
    {
        $response = $this->get('telephony/calls/'.$callId, []);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new CallEntity($response->data);
    }

    /**
     * List call history.
     *
     * @param  array<string, mixed>|null  $additional_data
     * @return CallHistoryItemEntity[]|Error
     */
    public function history(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, ['max', 'from', 'to']);

        $response = $this->get('telephony/calls/history', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($item) => new CallHistoryItemEntity($item), $items);
    }
}
