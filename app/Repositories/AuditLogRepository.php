<?php

namespace App\Repositories;

use App\Models\AuditLog;

readonly class AuditLogRepository extends BaseRepository
{
    public function __construct(AuditLog $model)
    {
        parent::__construct($model);
    }


}
