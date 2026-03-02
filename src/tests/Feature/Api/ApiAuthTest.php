<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_via_api(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email', 'created_at'],
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }

    public function test_registration_requires_valid_email(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_registration_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_can_login_via_api(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email'],
                'token',
            ]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_user_can_get_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
    }

    public function test_unauthenticated_user_cannot_access_protected_routes(): void
    {
        $response = $this->getJson('/api/v1/auth/me');

        $response->assertStatus(401);
    }

    public function test_user_can_logout_via_api(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $response = $this->withToken($token->plainTextToken)
            ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Déconnexion réussie.']);

        $this->assertCount(0, $user->fresh()->tokens);
    }

    public function test_registration_password_is_hashed(): void
    {
        $plainPassword = 'password123';

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'hashed-test@example.com',
            'password' => $plainPassword,
            'password_confirmation' => $plainPassword,
        ]);

        $response->assertStatus(201);

        $user = User::where('email', 'hashed-test@example.com')->first();

        $this->assertNotEquals($plainPassword, $user->password);
        $this->assertTrue(password_verify($plainPassword, $user->password));
    }

    public function test_registration_with_weak_password_fails(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'weak@example.com',
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ]);

        $response->assertStatus(422);
    }

    public function test_registration_without_letters_fails(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'noletters@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertStatus(422);
    }

    public function test_registration_without_numbers_fails(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'nonumbers@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422);
    }

    public function test_api_requires_token_for_protected_routes(): void
    {
        $response = $this->getJson('/api/v1/auth/me');

        $response->assertStatus(401);
    }

    public function test_api_with_invalid_token_rejected(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer invalid-token')
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(401);
    }

    public function test_logout_invalidates_specific_token(): void
    {
        $user = User::factory()->create();
        $token1 = $user->createToken('token-1');
        $token2 = $user->createToken('token-2');

        $this->withToken($token1->plainTextToken)
            ->postJson('/api/v1/auth/logout');

        $this->assertCount(1, $user->fresh()->tokens);
        $this->assertTrue($user->fresh()->tokens->contains('name', 'token-2'));
    }
}
