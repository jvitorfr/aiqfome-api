<?php

namespace App\Services\External;

use App\Services\Logging\Events\ThirdPartyFailureEvent;
use App\Services\Logging\ThirdPartyLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

readonly class ThirdPartyProductsClient
{
    private HttpClientInterface $http;

    public function __construct(
        private string $baseUrl = '',
        private int $timeout = 5
    ) {
        $this->http = HttpClient::create([
            'base_uri' => $this->baseUrl ?: config('services.fakestore.base_uri'),
            'timeout'  => $this->timeout,
        ]);
    }

    public function getProductById(int $id): ?array
    {
        try {
            $response = $this->http->request('GET', "/products/{$id}");

            if ($response->getStatusCode() === 200) {
                return $response->toArray();
            }
        } catch (Throwable $e) {
            ThirdPartyLogger::warning('Erro em getProductById', new ThirdPartyFailureEvent(
                service: 'FakeStore',
                action: 'getProductById',
                payload: ['product_id' => $id],
                error: $e->getMessage(),
                tags: ['product', 'fetch']
            ));
        }

        return null;
    }

    public function getAll(): array
    {
        try {
            $response = $this->http->request('GET', '/products');
            return $response->toArray();
        } catch (Throwable $e) {
            ThirdPartyLogger::warning('Erro em getAll', new ThirdPartyFailureEvent(
                service: 'FakeStore',
                action: 'getAll',
                payload: [],
                error: $e->getMessage(),
                tags: ['product', 'fetch']
            ));
        }

        return [];
    }

    public function updateProduct(int $id, array $data): ?array
    {
        try {
            $response = $this->http->request('PUT', "/products/{$id}", [
                'json' => $data,
            ]);

            if ($response->getStatusCode() === 200) {
                return $response->toArray();
            }
        } catch (Throwable $e) {
            ThirdPartyLogger::warning('Erro em updateProduct', new ThirdPartyFailureEvent(
                service: 'FakeStore',
                action: 'updateProduct',
                payload: ['product_id' => $id, 'data' => $data],
                error: $e->getMessage(),
                tags: ['product', 'update']
            ));
        }

        return null;
    }

    public function deleteProduct(int $id): bool
    {
        try {
            $response = $this->http->request('DELETE', "/products/{$id}");
            return $response->getStatusCode() === 200;
        } catch (Throwable $e) {
            ThirdPartyLogger::warning('Erro em deleteProduct', new ThirdPartyFailureEvent(
                service: 'FakeStore',
                action: 'deleteProduct',
                payload: ['product_id' => $id],
                error: $e->getMessage(),
                tags: ['product', 'delete']
            ));
        }

        return false;
    }
}
