<?php

namespace Tests\Unit\Services\Logging;

use App\Enums\AuditAction;
use App\Models\User;
use App\Repositories\Contracts\IAuditLogRepository;
use App\Services\Logging\AuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_logs_created_client_action()
    {
        $actor = User::factory()->create();

        $this->actingAs($actor, 'sanctum');

        $mockRepo = \Mockery::mock(IAuditLogRepository::class);
        $mockRepo->shouldReceive('create')->once()->withArgs(function ($data) use ($actor) {
            return $data['actor_id'] === $actor->id
                && $data['actor_type'] === $actor->getTable()
                && $data['action'] === AuditAction::CREATED_CLIENT->value
                && json_decode($data['after'], true)['name'] === 'Cliente XPTO';
        });

        $service = new AuditService($mockRepo);

        $service->log(
            action: AuditAction::CREATED_CLIENT,
            after: ['name' => 'Cliente XPTO']
        );
    }

    public function test_it_logs_add_favorite_product_action_with_metadata()
    {
        $actor = User::factory()->create();

        $this->actingAs($actor, 'sanctum');

        $mockRepo = \Mockery::mock(IAuditLogRepository::class);
        $mockRepo->shouldReceive('create')->once()->withArgs(function ($data) use ($actor) {
            return $data['actor_id'] === $actor->id
                && $data['actor_type'] === $actor->getTable()
                && $data['action'] === AuditAction::ADD_FAVORITE_PRODUCT->value
                && json_decode($data['metadata'], true)['product_id'] === 99;
        });

        $service = new AuditService($mockRepo);

        $service->log(
            action: AuditAction::ADD_FAVORITE_PRODUCT,
            metadata: ['product_id' => 99]
        );
    }

    public function test_it_does_not_log_without_authenticated_actor()
    {
        $mockRepo = \Mockery::mock(IAuditLogRepository::class);
        $mockRepo->shouldNotReceive('create');

        $service = new AuditService($mockRepo);

        $service->log(AuditAction::EDITED_CLIENT);
    }
}
