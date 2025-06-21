<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use App\Models\Favorite;
use App\Services\Enums\AuditAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminFavoriteTest extends TestCase
{
    use RefreshDatabase;

    private string $token;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'https://fakestoreapi.com/products/1' => Http::response([
                'id' => 1,
                'title' => 'Product 1',
                'price' => 100.0,
                'description' => '...',
                'category' => 'electronics',
                'image' => 'url',
                'rating' => ['rate' => 4.5, 'count' => 120],
            ]),
        ]);

        User::factory()->create([
            'email' => 'admin@aiqfome.com',
            'name' => 'Admin User',
            'password' => Hash::make('senhaSegura123'),
        ]);

        $this->client = Client::factory()->create();

          $auth = $this->postJson('/api/admin/login', [
            'email' => 'admin@aiqfome.com',
            'password' => 'senhaSegura123',
        ])->json();

    $this->token = $auth['data']['token'];
    $this->actingAs(User::where('email', 'admin@aiqfome.com')->first(), 'sanctum');

    }

    public function test_admin_can_add_favorite()
    {
        $response = $this->postJson("/api/admin/clients/{$this->client->id}/favorites/plus", [
            'product_id' => 1
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('favorites', [
            'client_id' => $this->client->id,
            'product_id' => 1
        ]);
    }

    public function test_admin_can_remove_favorite()
    {
        Favorite::factory()->create([
            'client_id' => $this->client->id,
            'product_id' => 1
        ]);

        $response = $this->postJson("/api/admin/clients/{$this->client->id}/favorites/minus", [
            'product_id' => 1
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);
        
        $response->assertOk();

        $this->assertDatabaseMissing('favorites', [
            'client_id' => $this->client->id,
            'product_id' => 1
        ]);
    }

    public function test_admin_cannot_add_invalid_product()
    {
        Http::fake([
            'https://fakestoreapi.com/products/9999' => Http::response([], 404)
        ]);

        $response = $this->postJson("/api/admin/clients/{$this->client->id}/favorites/plus", [
            'product_id' => 9999
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);

        $response->assertUnprocessable();
    }
}
