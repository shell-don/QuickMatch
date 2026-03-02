<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminUserCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    private function getAdmin(): User
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        return $admin;
    }

    public function test_admin_can_view_user_list(): void
    {
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->getAdmin())
            ->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('Utilisateurs');
    }

    public function test_admin_can_create_user(): void
    {
        $response = $this->actingAs($this->getAdmin())
            ->post('/admin/users', [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'roles' => ['user'],
            ]);

        $response->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name' => 'New User',
        ]);
    }

    public function test_admin_can_edit_user(): void
    {
        $user = User::factory()->create(['email' => 'old@example.com']);

        $response = $this->actingAs($this->getAdmin())
            ->put("/admin/users/{$user->id}", [
                'name' => 'Updated User',
                'email' => 'updated@example.com',
                'password' => '',
                'roles' => [],
            ]);

        $response->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'email' => 'updated@example.com',
            'name' => 'Updated User',
        ]);
    }

    public function test_admin_can_delete_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->getAdmin())
            ->delete("/admin/users/{$user->id}");

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)
            ->delete("/admin/users/{$admin->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_assign_role_to_user(): void
    {
        $user = User::factory()->create();
        $managerRole = Role::firstOrCreate(['name' => 'manager']);

        $response = $this->actingAs($this->getAdmin())
            ->put("/admin/users/{$user->id}", [
                'name' => $user->name,
                'email' => $user->email,
                'password' => '',
                'roles' => ['manager'],
            ]);

        $response->assertRedirect('/admin/users');

        $this->assertTrue($user->fresh()->hasRole('manager'));
    }
}
