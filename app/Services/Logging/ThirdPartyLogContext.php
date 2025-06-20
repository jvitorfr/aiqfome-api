<?php

namespace App\Services\Logging;

use App\Services\Logging\Contracts\ThirdPartyLoggable;

class ThirdPartyLogContext
{
    public static function from(ThirdPartyLoggable $event): array
    {
        return [
            'source'   => 'thirdparty',
            'service'  => $event->getServiceName(),
            'action'   => $event->getAction(),
            'status'   => $event->getStatus(),
            'error'    => $event->getError(),
            'payload'  => $event->getPayload(),
            'tags'     => $event->getTags(),
            'timestamp' => now()->toISOString(),
        ];
    }
}
