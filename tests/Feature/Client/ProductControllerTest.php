<?php

namespace Feature\Client;

use App\Models\Client;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private function authHeaders(Client $client): array
    {
        $token = JWTAuth::fromUser($client);
        return ['Authorization' => "Bearer $token"];
    }

    public function test_client_can_list_products()
    {
        $client = Client::factory()->create();

        $this->mock(ProductService::class, function ($mock) {
            $mock->shouldReceive('getAll')->once()->andReturn([
                ['id' => 1, 'title' => 'Produto A']
            ]);
        });

        $response = $this->getJson('/api/products', $this->authHeaders($client));

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Produto A']);
    }

    public function test_client_can_view_single_product()
    {
        $client = Client::factory()->create();

        $this->mock(ProductService::class, function ($mock) {
            $mock->shouldReceive('getById')->with(1)->once()->andReturn([
                'id' => 1,
                'title' => 'Produto A',
            ]);
        });

        $response = $this->getJson('/api/products/1', $this->authHeaders($client));

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Produto A']);
    }

    public function test_client_gets_404_for_invalid_product()
    {
        $client = Client::factory()->create();

        $this->mock(ProductService::class, function ($mock) {
            $mock->shouldReceive('getById')->with(999999)->once()->andReturn(null);
        });

        $response = $this->getJson('/api/products/999999', $this->authHeaders($client));

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Produto não encontrado']);
    }


}
