<?php

namespace App\Services\Logging;

use App\Enums\AuditAction;
use App\Repositories\AuditLogRepository;
use Illuminate\Database\Eloquent\Model;

readonly class AuditService
{
    public function __construct(
        private AuditLogRepository $repository
    ) {
    }

    /**
     */
    public function log(
        AuditAction $action,
        ?Model      $target = null,
        array       $before = [],
        array       $after = [],
        array       $metadata = []
    ): void {

        /** @var Model $actor */
        $actor = auth('sanctum')->user()
            ?? auth('api')->user()
            ?? auth()->user();

        if (!$actor) {
            return;
        }

        $this->repository->create([
            'actor_id' => $actor->getKey(),
            'actor_type' => $actor::class,

            'target_id' => $target?->getKey(),
            'target_type' => $target ? $target::class : null,

            'action' => $action->value,
            'before' => !empty($before) ? json_encode($before, JSON_UNESCAPED_UNICODE) : null,
            'after' => !empty($after) ? json_encode($after, JSON_UNESCAPED_UNICODE) : null,
            'metadata' => !empty($metadata) ? json_encode($metadata, JSON_UNESCAPED_UNICODE) : null,
        ]);
    }

}
