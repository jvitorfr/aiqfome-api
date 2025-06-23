<?php

namespace App\Repositories;

use App\Models\AuditLog;
use App\Repositories\Contracts\IAuditLogRepository;

readonly class AuditLogRepository extends BaseRepository implements IAuditLogRepository
{
    public function __construct(AuditLog $model)
    {
        parent::__construct($model);
    }
}
