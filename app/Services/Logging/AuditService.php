<?php

namespace App\Services\Logging;

use App\Enums\AuditAction;
use App\Models\AuditLog;
use App\Repositories\Contracts\IAuditLogRepository;
use Illuminate\Database\Eloquent\Model;

readonly class AuditService
{
    public function __construct(
        private IAuditLogRepository $repository
    ) {
    }

    /**
     * @param AuditAction $action
     * @param Model|null $actor
     * @param Model|null $target
     * @param array $before
     * @param array $after
     * @param array $metadata
     * @return AuditLog|null|Model
     */
    public function log(
        AuditAction $action,
        ?Model $actor = null,
        ?Model      $target = null,
        array       $before = [],
        array       $after = [],
        array       $metadata = [],
    ): AuditLog|Model|null
    {

        if (is_null($actor)) {
            /** @var Model $actor */
            $actor = auth('sanctum')->user()
                ?? auth('api')->user()
                ?? auth()->user();
        }

        if (!$actor) {
            return null;
        }

       return $this->repository->create([
            'actor_id' => $actor?->getKey() ?? null,
            'actor_type' => $actor->getTable(),

            'target_id' => $target?->getKey(),
            'target_type' => $target ? $target::class : null,

            'action' => $action->value,
            'before' => !empty($before) ? json_encode($before, JSON_UNESCAPED_UNICODE) : null,
            'after' => !empty($after) ? json_encode($after, JSON_UNESCAPED_UNICODE) : null,
            'metadata' => !empty($metadata) ? json_encode($metadata, JSON_UNESCAPED_UNICODE) : null,
        ]);
    }

}
