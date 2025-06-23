<?php

namespace App\Repositories\Contracts;

use App\Models\Favorite;
use Illuminate\Support\Collection;

interface IFavoriteRepository extends IBaseRepository
{
    public function getByClientId(int $clientId): Collection;

    public function getOne(int $clientId, int $productId): ?Favorite;

    public function exists(int $clientId, int $productId): bool;

}
