<?php

namespace App\Events;

use App\Enums\AuditAction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuditLogEvent implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly AuditAction $action,
        public readonly ?Model $actor = null,
        public readonly ?Model $target = null,
        public readonly array $before = [],
        public readonly array $after = [],
        public readonly array $metadata = [],
    ) {
    }
}
