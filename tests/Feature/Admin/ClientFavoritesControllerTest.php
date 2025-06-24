<?php

namespace Tests\Feature\Admin;

use App\Models\Client;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientFavoritesControllerTest extends TestCase
{
    use RefreshDatabase;

    private Client $client;
    private string $token;


    protected function mockThirdPartyLogger(): void
    {
        $mock = \Mockery::mock(\App\Services\Logging\ThirdPartyLogger::class);
        $mock->shouldReceive('warning')->once();

        $this->app->instance(\App\Services\Logging\ThirdPartyLogger::class, $mock);
    }

    private function createFakeUser(): string
    {
        $admin = User::factory()->create([
            'email' => 'admin@aiqfome.com',
            'password' => bcrypt('senhaSegura123'),
        ]);

        $token = $admin->createToken('test-token')->plainTextToken;
        $this->actingAs($admin, 'sanctum');
        return $token;
    }

    public function test_admin_cannot_access_without_authentication(): void
    {
        $client = Client::factory()->create();
        $response = $this->withHeaders([
            'Authorization' => '',
        ])->getJson("/api/admin/clients/$client->id/favorites");

        $response->assertStatus(401);
    }

    public function test_client_authenticated_via_api_is_unauthorized_on_admin_routes(): void
    {
        $client = Client::factory()->create();
        $this->actingAs($client, 'api');

        $response = $this->getJson("/api/admin/clients/$client->id/favorites");
        $response->assertStatus(401);
    }

    public function test_empty_favorites_list(): void
    {
        $client = Client::factory()->has(Favorite::factory()->count(0))->create();
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->createFakeUser()}"
        ])->getJson("/api/admin/clients/$client->id/favorites");

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    public function test_cannot_favorite_with_missing_product_id(): void
    {
        $client = Client::factory()->create();
        $response = $this->postJson("/api/admin/clients/$client->id/favorites/", [], [
            'Authorization' => "Bearer {$this->createFakeUser()}"
        ]);

        $response->assertMethodNotAllowed();
    }

    public function test_cannot_favorite_with_invalid_product_id(): void
    {
        $this->mockThirdPartyLogger();
        $client = Client::factory()->create();

        $response = $this->postJson("/api/admin/clients/{$client->id}/favorites/999", [], [
            'Authorization' => "Bearer {$this->createFakeUser()}"
        ]);
        $response->assertStatus(404);
    }

    public function test_favoriting_invalid_external_product_returns_404(): void
    {
        $this->mockThirdPartyLogger();
        $client = Client::factory()->create();

        $response = $this->postJson("/api/admin/clients/{$client->id}/favorites/999999", [], [
            'Authorization' => "Bearer {$this->createFakeUser()}"
        ]);

        $response->assertStatus(404);
    }


    public function test_duplicate_favorite_is_rejected(): void
    {
        $client = Client::factory()->create();
        Favorite::factory()->create([
            'client_id' => $client->id,
            'product_id' => 1
        ]);

        $response = $this->postJson("/api/admin/clients/$client->id/favorites/1", [
        ], [
            'Authorization' => "Bearer {$this->createFakeUser()}"
        ]);

        $response->assertStatus(422);
    }

    public function test_removing_nonexistent_favorite_is_graceful(): void
    {
        $client = Client::factory()->create();
        $response = $this->deleteJson("/api/admin/clients/$client->id/favorites/9999", [], [
            'Authorization' => "Bearer {$this->createFakeUser()}"
        ]);

        $response->assertStatus(404);
        $this->assertDatabaseMissing('favorites', [
            'client_id' => $client->id,
            'product_id' => 9999,
        ]);
    }
}
