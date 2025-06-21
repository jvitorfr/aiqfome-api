<?php

namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache;

class ProductCacheService
{
    public function getFavoritesFromCache(int $clientId, callable $callback): array
    {
        return Cache::tags(["client:$clientId"])
            ->remember('client_favorites', now()->addMinutes(6), $callback);
    }

    public function clearFavoritesCache(int $clientId): void
    {
        Cache::tags(["client:$clientId"])->forget('client_favorites');
    }

    public function getProductFromCache(int $productId, callable $callback): ?array
    {
        return Cache::remember("product_$productId", now()->addHours(6), $callback);
    }

    public function warmProduct(int $productId, array $product): void
    {
        Cache::put("product_$productId", $product, now()->addHours(6));
    }

    public function clearProduct(int $productId): void
    {
        Cache::forget("product_$productId");
    }
}
