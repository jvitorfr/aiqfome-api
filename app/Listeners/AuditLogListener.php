<?php

namespace App\Listeners;

use App\Events\AuditLogEvent;
use App\Facades\ThirdPartyLogger;
use App\Services\Logging\AuditService;
use App\Services\Logging\Events\ThirdPartyFailureEvent;
use Mockery\Exception;

class AuditLogListener
{
    public function __construct(protected AuditService $auditService) {}

    public function handle(AuditLogEvent $event): void
    {
        $this->auditService->log(
            action: $event->action,
            actor: $event->actor,
            target: $event->target,
            before: $event->before,
            after: $event->after,
            metadata: $event->metadata
        );
    }
}
