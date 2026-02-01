<?php

namespace Offlineagency\LaravelWebex\Api\Admin;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Admin\AdminAuditEvent as AdminAuditEventEntity;
use Offlineagency\LaravelWebex\Entities\Error;

class AdminAuditEvents extends AbstractApi
{
    /**
     * List admin audit events (requires audit:events_read scope).
     *
     * @return AdminAuditEventEntity[]|Error
     */
    public function list(?array $additional_data = [])
    {
        $additional_data = $this->data($additional_data, [
            'max', 'from', 'to', 'actorId', 'event',
        ]);

        $response = $this->get('adminAuditEvents', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(fn ($event) => new AdminAuditEventEntity($event), $items);
    }
}
