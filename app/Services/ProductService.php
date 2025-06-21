<?php

namespace App\Services;

use App\Enums\AuditAction;
use App\Repositories\FavoriteRepository;
use App\Services\Cache\ProductCacheService;
use App\Services\External\ThirdPartyProductsClient;
use App\Services\Logging\AuditService;

class ProductService
{
    public function __construct(
        protected ThirdPartyProductsClient $external,
        protected FavoriteRepository       $repository,
        protected ProductCacheService      $cache,
        protected AuditService      $audit,
    ) {
    }

    public function getAll(): array
    {
        return $this->external->getAll();
    }

    public function getById(int $id): ?array
    {
        return $this->external->getProductById($id);
    }

    public function update(int $id, array $data): ?array
    {
        return $this->external->updateProduct($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->external->deleteProduct($id);
    }


    /**
     */
    public function getFavoritesByClientId(int $clientId): array
    {
        return $this->cache->getFavoritesFromCache($clientId, function () use ($clientId) {
            $favorites = $this->repository->getByClientId($clientId)->keyBy('product_id');

            if ($favorites->isEmpty()) {
                return [];
            }

            $products = [];

            foreach ($favorites as $productId => $favorite) {
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
     */
    public function incrementFavorite(int $clientId, int $productId): ?array
    {
        $product = $this->external->getProductById($productId);

        if (!$product) {
            return null;
        }

        $existing = $this->repository->getOne($clientId, $productId);
        $before = $existing?->toArray();

        $favorite = $this->repository->incrementOrCreate($clientId, $productId);

        $this->cache->clearFavoritesCache($clientId);

        $this->audit->log(
            action:  empty($before) ? AuditAction::ADD_FAVORITE_PRODUCT : AuditAction::EDIT_FAVORITE_PRODUCT,
            data: [
                'product_id' => $productId,
                'before'     => $before,
                'after'      => $favorite->toArray()
            ],
            clientId: $clientId
        );

        return array_merge(
            $product,
            ['quantity' => $favorite->quantity]
        );
    }

    /**
     */
    public function decrementFavorite(int $clientId, int $productId): bool
    {
        $before = $this->repository->getByClientId($clientId)
            ->firstWhere('product_id', $productId);

        $success = $this->repository->decrementOrDelete($clientId, $productId);

        if ($success) {
            $this->cache->clearFavoritesCache($clientId);

            $this->audit->log(
                action: $before?->quantity === 1
                    ? AuditAction::REMOVED_FAVORITE_PRODUCT
                    : AuditAction::EDIT_FAVORITE_PRODUCT,
                data: [
                    'product_id' => $productId,
                    'before'     => $before?->toArray(),
                    'after'      => null,
                ],
                clientId: $clientId
            );
        }

        return $success;
    }


}
