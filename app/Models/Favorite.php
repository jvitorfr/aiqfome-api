<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $client_id
 * @property int $product_id
 * @property int $quantity
 */
class Favorite extends Model
{
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
