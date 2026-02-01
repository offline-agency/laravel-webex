<?php

namespace Offlineagency\LaravelWebex\Api\Devices;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;

class Xapi extends AbstractApi
{
    /**
     * Send xAPI command to a device.
     *
     * @return object|Error
     */
    public function command(string $deviceId, string $command, array $arguments = [])
    {
        $body = array_merge(['command' => $command], $arguments);

        $response = $this->post('xapi/commands/'.$deviceId, $body);

        if (! $response->success) {
            return new Error($response->data);
        }

        return $response->data;
    }

    /**
     * Get xAPI status from a device.
     *
     * @return object|Error
     */
    public function status(string $deviceId, string $path)
    {
        $response = $this->get('xapi/status/'.$deviceId, ['path' => $path]);

        if (! $response->success) {
            return new Error($response->data);
        }

        return $response->data;
    }
}
