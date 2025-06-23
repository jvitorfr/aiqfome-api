<?php

namespace App\Services;

use App\Enums\AuditAction;
use App\Events\AuditLogEvent;
use App\Repositories\Contracts\IFavoriteRepository;
use App\Services\Cache\ProductCacheService;
use App\Services\External\ThirdPartyProductsClient;
use App\Services\Logging\AuditService;
use Throwable;

class ProductService
{
    public function __construct(
        protected ThirdPartyProductsClient $external,
        protected IFavoriteRepository       $repository,
        protected ProductCacheService       $cache,
        protected AuditService              $audit,
    ) {
    }

    public function getAll(): array
    {
        return $this->cache->getAllProductsFromCache(function () {
            return $this->external->getAll();
        });
    }

    public function getById(int $id): ?array
    {
        return $this->cache->getProductFromCache($id, function () use ($id) {
            return $this->external->getProductById($id);
        });
    }

    public function getFavoritesByClientId(int $clientId): array
    {
        return $this->cache->getFavoritesFromCache($clientId, function () use ($clientId) {
            $favorites = $this->repository->getByClientId($clientId);

            if ($favorites->isEmpty()) {
                return [];
            }

            $products = [];

            foreach ($favorites->keyBy('product_id') as $productId => $favorite) {
                $product = $this->cache->getProductFromCache(
                    $productId,
                    fn () => $this->external->getProductById($productId)
                );

                if ($product) {
                    $products[] = array_merge($product, ['quantity' => $favorite->quantity]);
                }
            }

            return $products;
        });
    }

    /**
     * @throws Throwable
     */
    public function addFavorite(int $clientId, int $productId): ?array
    {
        $product = $this->external->getProductById($productId);
        abort_if(!$product, 404);

        $alreadyExists = $this->repository->getOne($clientId, $productId);
        if ($alreadyExists) {
            return null;
        }

        $favorite = $this->repository->create(['client_id' => $clientId, 'product_id' => $productId]);

        $this->cache->clearFavoritesCache($clientId);

        event(new AuditLogEvent(
            action: AuditAction::ADD_FAVORITE_PRODUCT,
            target: $favorite,
            before: [],
            after: $favorite->toArray(),
            metadata: ['product_id' => $productId]
        ));

        return $product;
    }

    public function removeFavorite(int $clientId, int $productId): bool
    {
        $favorite = $this->repository->getOne($clientId, $productId);
        if (!$favorite) {
            return false;
        }

        $before = $favorite->toArray();
        $this->repository->delete($favorite);
        $this->cache->clearFavoritesCache($clientId);

        event(new AuditLogEvent(
            action: AuditAction::REMOVED_FAVORITE_PRODUCT,
            target: $favorite,
            before: $before,
            after: [],
            metadata: ['product_id' => $productId]
        ));
        return true;
    }
}
