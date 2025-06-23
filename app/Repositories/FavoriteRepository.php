<?php

namespace App\Repositories;

use App\Models\Favorite;
use App\Repositories\Contracts\IFavoriteRepository;
use Illuminate\Support\Collection;

readonly class FavoriteRepository extends BaseRepository implements IFavoriteRepository
{
    public function __construct(Favorite $model)
    {
        parent::__construct($model);
    }

    public function getByClientId(int $clientId): Collection
    {
        return $this->where('client_id', $clientId)->get();
    }

    public function getOne(int $clientId, int $productId): ?Favorite
    {
        return $this->query()
            ->where('client_id', $clientId)
            ->where('product_id', $productId)
            ->first();
    }

    public function exists(int $clientId, int $productId): bool
    {
        return $this->where('client_id', $clientId)
            ->where('product_id', $productId)
            ->exists();
    }


}
