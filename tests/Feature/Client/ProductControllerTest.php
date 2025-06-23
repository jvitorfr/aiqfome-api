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

        $response = $this->getJson('/api/client/products', $this->authHeaders($client));

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

        $response = $this->getJson('/api/client/products/1', $this->authHeaders($client));

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Produto A']);
    }

    public function test_client_gets_404_for_invalid_product()
    {
        $client = Client::factory()->create();

        $this->mock(ProductService::class, function ($mock) {
            $mock->shouldReceive('getById')->with(999999)->once()->andReturn(null);
        });

        $response = $this->getJson('/api/client/products/999999', $this->authHeaders($client));

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Produto não encontrado']);
    }

    public function test_client_can_update_product()
    {
        $client = Client::factory()->create();

        $payload = ['title' => 'Atualizado', 'price' => 10.5];

        $this->mock(ProductService::class, function ($mock) use ($payload) {
            $mock->shouldReceive('update')->with(1, $payload)->once()->andReturn(array_merge(['id' => 1], $payload));
        });

        $response = $this->putJson('/api/client/products/1', $payload, $this->authHeaders($client));

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Atualizado']);
    }

    public function test_client_fails_to_update_invalid_product()
    {
        $client = Client::factory()->create();

        $this->mock(ProductService::class, function ($mock) {
            $mock->shouldReceive('update')->with(999999, ['title' => 'X'])->once()->andReturn(false);
        });

        $response = $this->putJson('/api/client/products/999999', ['title' => 'X'], $this->authHeaders($client));

        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Erro ao atualizar produto']);
    }

    public function test_client_can_delete_product()
    {
        $client = Client::factory()->create();

        $this->mock(ProductService::class, function ($mock) {
            $mock->shouldReceive('delete')->with(1)->once()->andReturn(true);
        });

        $response = $this->deleteJson('/api/client/products/1', [], $this->authHeaders($client));

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Produto removido com sucesso']);
    }

    public function test_client_fails_to_delete_invalid_product()
    {
        $client = Client::factory()->create();

        $this->mock(ProductService::class, function ($mock) {
            $mock->shouldReceive('delete')->with(999999)->once()->andReturn(false);
        });

        $response = $this->deleteJson('/api/client/products/999999', [], $this->authHeaders($client));

        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Falha ao remover produto']);
    }
}
