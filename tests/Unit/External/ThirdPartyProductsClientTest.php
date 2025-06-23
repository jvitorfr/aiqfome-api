<?php

namespace Tests\Unit\Services\External;

use App\Services\External\ThirdPartyProductsClient;
use App\Services\Logging\ThirdPartyLogger;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ThirdPartyProductsClientTest extends TestCase
{
    public function test_get_product_by_id_returns_data()
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('toArray')->willReturn(['id' => 1, 'title' => 'Produto A']);

        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->expects($this->once())
            ->method('request')
            ->with('GET', '/products/1')
            ->willReturn($mockResponse);

        $client = new ThirdPartyProductsClient(baseUrl: 'http://fake.api');
        $this->setPrivateProperty($client, 'http', $mockHttp); // injeta o mock no lugar

        $result = $client->getProductById(1);

        $this->assertIsArray($result);
        $this->assertEquals('Produto A', $result['title']);
    }

    public function test_get_product_by_id_handles_exception_and_returns_null()
    {
        // Seta o root app falso para permitir uso da Facade
        Facade::setFacadeApplication(app());

        ThirdPartyLogger::shouldReceive('warning')->once();

        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->method('request')->willThrowException(new \Exception('Erro fake'));

        $client = new ThirdPartyProductsClient(baseUrl: 'http://fake.api', http: $mockHttp);

        $result = $client->getProductById(999);

        $this->assertNull($result);
    }

    public function test_get_all_returns_array()
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('toArray')->willReturn([
            ['id' => 1, 'title' => 'Produto A']
        ]);

        $mockHttp = $this->createMock(HttpClientInterface::class);
        $mockHttp->method('request')->with('GET', '/products')->willReturn($mockResponse);

        $client = new ThirdPartyProductsClient(baseUrl: 'http://fake.api');
        $this->setPrivateProperty($client, 'http', $mockHttp);

        $result = $client->getAll();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }

    private function setPrivateProperty(object $object, string $property, mixed $value): void
    {
        $ref = new \ReflectionProperty($object, $property);
        $ref->setAccessible(true);
        $ref->setValue($object, $value);
    }
}
