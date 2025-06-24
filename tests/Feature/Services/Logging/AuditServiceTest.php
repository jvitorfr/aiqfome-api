<?php

namespace Tests\Feature\Services\Logging;

use App\Enums\AuditAction;
use App\Models\AuditLog;
use App\Models\Client;
use App\Models\User;
use App\Services\External\ThirdPartyProductsClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_audit_log_when_favoriting_product()
    {
        // Arrange
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $productId = 123;

        $this->actingAs($user, 'sanctum');

        $this->mock(ThirdPartyProductsClient::class, function ($mock) use ($productId) {
            $mock->shouldReceive('getProductById')->with($productId)->andReturn([
                'id' => $productId,
                'name' => 'Mock Product',
            ]);
        });

        $this->postJson("/api/admin/clients/$client->id/favorites/$productId", [
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'actor_id' => $user->id,
            'target_type' => 'App\Models\Favorite',
            'action' => AuditAction::ADD_FAVORITE_PRODUCT->value,
        ]);

        $log = AuditLog::latest()->first();
        $this->assertNotNull($log->after);
        $this->assertEquals($productId, json_decode($log->metadata, true)['product_id']);
    }
}
