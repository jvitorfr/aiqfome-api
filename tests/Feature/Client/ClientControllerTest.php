<?php

namespace Feature\Client;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;


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
    private function headers(): array
    {
        return ['Authorization' => 'Bearer ' . $this->createFakeUser()];
    }

    public function test_can_list_clients()
    {
        Client::factory()->count(3)->create();

        $response = $this->getJson('/api/admin/clients', $this->headers());

        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }

    public function test_can_show_client()
    {
        $client = Client::factory()->create();

        $response = $this->getJson("/api/admin/clients/{$client->id}", $this->headers());

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $client->id]);
    }

    public function test_can_create_client()
    {
        $data = [
            'name' => 'Cliente Teste',
            'email' => 'cliente@example.com',
            'password' => 'secret123',
        ];

        $response = $this->postJson('/api/admin/clients', $data, $this->headers());

        $response->assertStatus(201)
                 ->assertJsonFragment(['email' => $data['email']]);
    }

    public function test_can_update_client()
    {
        $client = Client::factory()->create();

        $updateData = ['name' => 'Novo Nome'];

        $response = $this->putJson("/api/admin/clients/{$client->id}", $updateData, $this->headers());

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Novo Nome']);
    }

    public function test_can_delete_client()
    {
        $client = Client::factory()->create();

        $response = $this->deleteJson("/api/admin/clients/{$client->id}", [], $this->headers());

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Client deleted']);

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }
}
