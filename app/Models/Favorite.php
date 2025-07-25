<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $client_id
 * @property int $product_id
 */
class Favorite extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'product_id'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
