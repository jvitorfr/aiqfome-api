<?php

namespace App\Services;

use App\Http\Clients\ThirdPartyProductsClient;
use App\Repositories\FavoriteRepository;

class ProductService
{
    public function __construct(
        protected ThirdPartyProductsClient $external,
        protected FavoriteRepository $repository,
    ) {}

    // Produtos externos (FakeStore)
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

    public function getFavoritesByClientId(int $clientId): array
    {
        $favorites = $this->repository
            ->getByClientId($clientId)
            ->keyBy('product_id');

        if ($favorites->isEmpty()) {
            return [];
        }

        $products = [];

        foreach ($favorites as $productId => $favorite) {
            $product = $this->external->getProductById($productId);

            if ($product) {
                $products[] = array_merge(
                    $product,
                    ['quantity' => $favorite->quantity]
                );
            }
        }

        return $products;
    }


    public function incrementFavorite(int $clientId, int $productId): ?array
    {
        $product = $this->external->getProductById($productId);

        if (!$product) {
            return null;
        }

        $favorite = $this->repository->incrementOrCreate($clientId, $productId);

        return array_merge(
            $product,
            ['quantity' => $favorite->quantity]
        );
    }

    public function decrementFavorite(int $clientId, int $productId): bool
    {
        return $this->repository->decrementOrDelete($clientId, $productId);
    }

}
