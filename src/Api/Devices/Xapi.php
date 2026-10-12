<?php

namespace Offlineagency\LaravelWebex\Api\Devices;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;

class Xapi extends AbstractApi
{
    /**
     * Send xAPI command to a device.
     *
     * @param  array<string, mixed>  $arguments
     * @return array<mixed>|object|null|Error
     */
    public function command(string $deviceId, string $command, array $arguments = [])
    {
        // POST /xapi/command/{commandName} with the device and the command arguments in the body
        $response = $this->post('xapi/command/'.$command, ['deviceId' => $deviceId, 'arguments' => $arguments]);

        if (! $response->success) {
            return new Error($response->data);
        }

        return $response->data;
    }

    /**
     * Get xAPI status from a device.
     *
     * @return array<mixed>|object|null|Error
     */
    public function status(string $deviceId, string $path)
    {
        $response = $this->get('xapi/status', ['deviceId' => $deviceId, 'name' => $path]);

        if (! $response->success) {
            return new Error($response->data);
        }

        return $response->data;
    }
}
