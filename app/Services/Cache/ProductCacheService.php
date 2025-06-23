<?php

namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache;

class ProductCacheService
{
    public function getFavoritesFromCache(int $clientId, callable $callback): array
    {
        $key = "client_{$clientId}_favorites";
        $store = Cache::tags(["client:$clientId"]);

        if (!$store->get($key)) {
            $value = $callback();
            $store->put($key, $value, now()->addMinutes(6));
            return $value;
        }

        return $store->get($key);
    }

    public function clearFavoritesCache(int $clientId): void
    {
        Cache::tags(["client:$clientId"])
            ->forget("client_{$clientId}_favorites");
    }

    public function getProductFromCache(int $productId, callable $callback): ?array
    {
        return Cache::remember("product_$productId", now()->addHours(6), $callback);
    }

    public function getAllProductsFromCache(callable $callback): array
    {
        $key = 'fakestore_products_all';

        if (!Cache::has($key)) {
            $value = $callback();
            Cache::put($key, $value, now()->addMinutes(10));
            return $value;
        }

        return Cache::get($key);
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
