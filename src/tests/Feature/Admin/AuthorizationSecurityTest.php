<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthorizationSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_role_cannot_access_admin(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_manager_role_cannot_access_admin_dashboard(): void
    {
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $user = User::factory()->create();
        $user->assignRole($managerRole);

        $response = $this->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_regular_user_cannot_access_user_management(): void
    {
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $user = User::factory()->create();
        $user->assignRole($userRole);

        $response = $this->actingAs($user)
            ->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_regular_user_cannot_access_role_management(): void
    {
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $user = User::factory()->create();
        $user->assignRole($userRole);

        $response = $this->actingAs($user)
            ->get('/admin/roles');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_all_admin_routes(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get('/admin/users')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get('/admin/roles')
            ->assertStatus(200);
    }

    public function test_user_cannot_access_other_user_data_via_api(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $token = $user1->createToken('user1-token');

        $response = $this->withToken($token->plainTextToken)
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
            ->assertJsonPath('user.id', $user1->id)
            ->assertJsonPath('user.email', $user1->email);

        $this->assertNotEquals($user2->email, $response->json('user.email'));
    }

    public function test_permission_inheritance_works_correctly(): void
    {
        $role = Role::firstOrCreate(['name' => 'test-inherit-perms']);
        $permission = Permission::firstOrCreate(['name' => 'test.custom']);
        $role->givePermissionTo($permission);

        $user = User::factory()->create();
        $user->assignRole($role);

        $this->assertTrue($user->hasPermissionTo('test.custom'));

        $user->removeRole($role);

        $this->assertFalse($user->hasPermissionTo('test.custom'));
    }

    public function test_guest_cannot_access_any_protected_route(): void
    {
        $webRoutes = [
            '/admin/dashboard',
            '/admin/users',
            '/admin/roles',
            '/dashboard',
        ];

        foreach ($webRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }

        $apiRoutes = [
            '/api/v1/auth/me',
        ];

        foreach ($apiRoutes as $route) {
            $response = $this->getJson($route);
            $response->assertStatus(401);
        }
    }
}
