<?php

namespace Tests\Unit\Services\External;

use App\Facades\ThirdPartyLogger;
use App\Services\External\ThirdPartyProductsClient;
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

        $client = new ThirdPartyProductsClient(baseUrl: 'http://fake.api', http: $mockHttp);

        $result = $client->getProductById(1);

        $this->assertIsArray($result);
        $this->assertEquals('Produto A', $result['title']);
    }

    public function test_get_product_by_id_handles_exception_and_returns_null()
    {
        Facade::setFacadeApplication(app());

        \Mockery::mock('alias:' . ThirdPartyLogger::class)
            ->shouldReceive('warning')
            ->once();

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
        $mockHttp->method('request')
            ->with('GET', '/products')
            ->willReturn($mockResponse);

        $client = new ThirdPartyProductsClient(baseUrl: 'http://fake.api', http: $mockHttp);

        $result = $client->getAll();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }
}
