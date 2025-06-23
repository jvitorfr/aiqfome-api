<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    private function authHeaders(Client $client): array
    {
        $token = JWTAuth::fromUser($client);
        return ['Authorization' => "Bearer {$token}"];
    }

    public function test_client_can_list_favorites()
    {
        $client = Client::factory()->create();

        $this->mock(ProductService::class, function ($mock) {
            $mock->shouldReceive('getFavoritesByClientId')->andReturn([]);
        });

        $response = $this->getJson('/api/client/favorites', $this->authHeaders($client));

        $response->assertStatus(200);
    }

    public function test_client_can_add_favorite()
    {
        $client = Client::factory()->create();
        $productId = 1;

        $this->mock(ProductService::class, function ($mock) use ($productId) {
            $mock->shouldReceive('addFavorite')->andReturn(['id' => $productId]);
        });

        $response = $this->postJson('/api/client/favorites', [
            'product_id' => $productId
        ], $this->authHeaders($client));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $productId]);
    }

    public function test_client_can_remove_favorite()
    {
        $client = Client::factory()->create();
        $productId = 1;

        $this->mock(ProductService::class, function ($mock) use ($productId) {
            $mock->shouldReceive('removeFavorite')->andReturn(true);
        });

        $response = $this->deleteJson(
            "/api/client/favorites/$productId",
            [],
            $this->authHeaders($client)
        );

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Removido com sucesso']);
    }

    public function test_invalid_favorite_returns_422()
    {
        $client = Client::factory()->create();

        $response = $this->postJson('/api/client/favorites', [
            'product_id' => null
        ], $this->authHeaders($client));

        $response->assertStatus(422);
    }
}
