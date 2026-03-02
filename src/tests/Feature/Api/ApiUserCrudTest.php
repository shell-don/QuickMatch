<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiUserCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'user', 'guard_name' => 'web']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->user = User::factory()->create();
        $this->user->assignRole('user');
    }

    public function test_admin_can_list_users(): void
    {
        User::factory()->count(5)->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/admin/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            ]);
    }

    public function test_non_admin_cannot_list_users(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/admin/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_user(): void
    {
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'roles' => ['user'],
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/users', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'name', 'email', 'roles'],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function test_admin_can_view_user(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/admin/users/{$this->user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $this->user->id,
                    'email' => $this->user->email,
                ],
            ]);
    }

    public function test_admin_can_update_user(): void
    {
        $updateData = [
            'name' => 'Updated Name',
            'email' => $this->user->email,
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/admin/users/{$this->user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Utilisateur mis à jour avec succès.',
            ]);

        $this->assertDatabaseHas('users', ['name' => 'Updated Name']);
    }

    public function test_admin_can_delete_user(): void
    {
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/users/{$userToDelete->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès.',
            ]);

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/users/{$this->admin->id}");

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_user_can_get_own_permissions(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/auth/permissions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['roles', 'permissions'],
            ]);
    }

    public function test_pagination_works(): void
    {
        User::factory()->count(20)->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/admin/users?per_page=5');

        $response->assertStatus(200);

        $meta = $response->json('meta');
        $this->assertArrayHasKey('per_page', $meta);
    }

    public function test_search_works(): void
    {
        User::factory()->create(['name' => 'UniqueJohnDoe', 'email' => 'uniquejohn@example.com']);
        User::factory()->create(['name' => 'Jane Doe', 'email' => 'jane@example.com']);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/admin/users?search=UniqueJohn');

        $response->assertStatus(200);
        $this->assertEquals(1, count($response->json('data')));
    }

    public function test_filter_by_role_works(): void
    {
        $admin2 = User::factory()->create();
        $admin2->assignRole('admin');

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/admin/users?role=admin');

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_protected_routes(): void
    {
        $response = $this->getJson('/api/v1/admin/users');

        $response->assertStatus(401);
    }
}
