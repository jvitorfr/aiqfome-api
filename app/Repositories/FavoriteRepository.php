<?php

namespace App\Repositories;

use App\Models\Favorite;
use Illuminate\Support\Collection;

readonly class FavoriteRepository extends BaseRepository
{
    public function __construct(Favorite $model)
    {
        parent::__construct($model);
    }

    public function getByClientId(int $clientId): Collection
    {
        return $this->where('client_id', $clientId)->get();
    }

    public function exists(int $clientId, int $productId): bool
    {
        return $this->where('client_id', $clientId)
            ->where('product_id', $productId)
            ->exists();
    }

    public function createIfNotExists(int $clientId, int $productId): Favorite
    {
        return $this->query()->firstOrCreate([
            'client_id' => $clientId,
            'product_id' => $productId,
        ]);
    }

    public function deleteByIdAndClient(int $favoriteId, int $clientId): bool
    {
        return $this->where('id', $favoriteId)
            ->where('client_id', $clientId)
            ->delete();
    }

    public function incrementOrCreate(int $clientId, int $productId): Favorite
    {
        /** @var Favorite $favorite */
        $favorite = $this->query()->firstOrNew([
            'client_id' => $clientId,
            'product_id' => $productId,
        ]);

        $favorite->quantity = ($favorite->quantity ?? 0) + 1;
        $favorite->save();

        return $favorite;
    }

    public function decrementOrDelete(int $clientId, int $productId): bool
    {
        $favorite = $this->query()->where('client_id', $clientId)
            ->where('product_id', $productId)
            ->first();

        if (!$favorite) {
            return false;
        }

        $favorite->quantity > 1 ? $favorite->decrement('quantity') : $favorite->delete();

        return true;
    }

}
