<?php

namespace Tests\Unit\Services\Cache;

use App\Services\Cache\ProductCacheService;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class ProductCacheServiceTest extends TestCase
{

    public function test_get_favorites_from_cache(): void
    {
        Cache::shouldReceive('tags')
            ->with(['client:1'])
            ->once()
            ->andReturnSelf();

        Cache::shouldReceive('remember')
            ->with(
                'client_favorites',
                Mockery::type(\DateTimeInterface::class),
                Mockery::type('Closure')
            )
            ->once()
            ->andReturn(['product_id' => 1]);

        $service = new ProductCacheService();
        $result = $service->getFavoritesFromCache(1, fn() => ['product_id' => 1]);

        $this->assertEquals(['product_id' => 1], $result);
    }

    public function test_clear_favorites_cache(): void
    {
        Cache::shouldReceive('tags')
            ->with(['client:1'])
            ->once()
            ->andReturnSelf();

        Cache::shouldReceive('forget')
            ->with('client_favorites')
            ->once();

        new ProductCacheService()->clearFavoritesCache(1);

        $this->assertTrue(true);
    }

    public function test_get_product_from_cache(): void
    {
        $productId = 5;
        $callback = fn () => ['id' => $productId];

        Cache::shouldReceive('remember')
            ->with(
                "product_$productId",
                Mockery::type(\DateTimeInterface::class),
                Mockery::type('Closure')
            )
            ->once()
            ->andReturn(['id' => $productId]);

        $result = new ProductCacheService()->getProductFromCache($productId, $callback);

        $this->assertEquals(['id' => $productId], $result);
    }

    public function test_warm_product(): void
    {
        $productId = 10;
        $product = ['id' => $productId, 'name' => 'Test'];

        Cache::shouldReceive('put')
            ->with(
                "product_$productId",
                $product,
                Mockery::type(\DateTimeInterface::class)
            )
            ->once();

        new ProductCacheService()->warmProduct($productId, $product);

        $this->assertTrue(true);
    }

    public function test_clear_product(): void
    {
        $productId = 15;

        Cache::shouldReceive('forget')
            ->with("product_$productId")
            ->once();

        new ProductCacheService()->clearProduct($productId);

        $this->assertTrue(true);
    }
}
