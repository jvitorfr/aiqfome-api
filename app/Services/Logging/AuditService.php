<?php

namespace App\Services\Logging;

use App\Enums\AuditAction;
use App\Repositories\AuditLogRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public function __construct(
        protected AuditLogRepository $repository
    ) {
    }

    public function log(
        AuditAction $action,
        array $data = [],
        ?int $clientId = null,
        ?int $userId = null,
        ?string $ip = null
    ): void {
        $this->repository->create([
            'action'     => $action->value,
            'data'       => json_encode($data),
            'ip_address' => $ip ?? Request::ip(),
            'client_id'  => $clientId ?? optional(Auth::guard('api')->user())->id,
            'user_id'    => $userId ?? optional(Auth::guard('sanctum')->user())->id,
        ]);
    }
}
