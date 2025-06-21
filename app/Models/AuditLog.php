<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AuditLog
 *
 * @property int $id
 * @property string $action
 * @property string ip_address
 * @property array|null $data
 * @property int|null $client_id
 * @property int|null $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'actor_id',
        'actor_type',
        'target_id',
        'target_type',
        'action',
        'before',
        'after',
        'metadata',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
