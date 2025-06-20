<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}
