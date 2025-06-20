<?php

namespace App\Http\Clients;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

readonly class ProductClient
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
        } catch (TransportExceptionInterface) {
            //
        }

        return null;
    }

    public function getAll(): array
    {
        try {
            $response = $this->http->request('GET', '/products');
            return $response->toArray();
        } catch (\Exception) {
            return [];
        }
    }
}
