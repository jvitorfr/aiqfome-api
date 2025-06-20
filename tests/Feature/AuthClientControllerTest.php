<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthClientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_register(): void
    {
        $response = $this->postJson('/api/client/register', [
            'name' => 'Cliente Teste',
            'email' => 'clientetest@example.com',
            'password' => 'senha123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'data' => ['token', 'client' => ['id', 'name', 'email']],
        ]);
    }

    public function test_client_can_login_with_valid_credentials(): void
    {
        $client = Client::create([
            'name' => 'Cliente Login',
            'email' => 'login@example.com',
            'password' => Hash::make('senha123'),
        ]);

        $response = $this->postJson('/api/client/login', [
            'email' => $client->email,
            'password' => 'senha123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => ['token', 'expires_in'],
        ]);
    }

    public function test_client_login_fails_with_invalid_credentials(): void
    {
        $client = Client::create([
            'name' => 'Cliente Login',
            'email' => 'invalido@example.com',
            'password' => Hash::make('senha123'),
        ]);

        $response = $this->postJson('/api/client/login', [
            'email' => $client->email,
            'password' => 'senha_errada',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'error' => 'Credenciais inválidas',
        ]);
    }

    public function test_client_cannot_register_with_existing_email(): void
    {
        Client::factory()->create([
            'email' => 'duplicado@example.com',
        ]);

        $response = $this->postJson('/api/client/register', [
            'name' => 'Outro Cliente',
            'email' => 'duplicado@example.com',
            'password' => 'senha123',
        ]);

        $response->assertStatus(422); // Laravel validation
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_client_can_access_protected_route(): void
    {
        $client = Client::factory()->create();
        $token = auth('api')->login($client);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/client/me');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $client->id,
                'email' => $client->email,
            ],
        ]);
    }

    public function test_registration_requires_name_email_password(): void
    {
        $response = $this->postJson('/api/client/register', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }


    public function test_client_can_logout(): void
    {
        $client = Client::factory()->create();
        $token = auth('api')->login($client);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/client/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Logout realizado com sucesso',
        ]);
    }

}
