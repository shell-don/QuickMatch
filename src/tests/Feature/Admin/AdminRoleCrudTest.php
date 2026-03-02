<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminRoleCrudTest extends TestCase
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

    public function test_admin_can_view_role_list(): void
    {
        Role::firstOrCreate(['name' => 'test-role']);

        $response = $this->actingAs($this->getAdmin())
            ->get('/admin/roles');

        $response->assertStatus(200);
        $response->assertSee('Rôles');
    }

    public function test_admin_can_create_role(): void
    {
        $response = $this->actingAs($this->getAdmin())
            ->post('/admin/roles', [
                'name' => 'custom-role',
                'permissions' => ['users.view'],
            ]);

        $response->assertRedirect('/admin/roles');

        $this->assertDatabaseHas('roles', [
            'name' => 'custom-role',
        ]);
    }

    public function test_admin_can_edit_role(): void
    {
        $role = Role::firstOrCreate(['name' => 'old-role']);

        $response = $this->actingAs($this->getAdmin())
            ->put("/admin/roles/{$role->id}", [
                'name' => 'updated-role',
                'permissions' => [],
            ]);

        $response->assertRedirect('/admin/roles');

        $this->assertDatabaseHas('roles', [
            'name' => 'updated-role',
        ]);
    }

    public function test_admin_can_delete_role(): void
    {
        $role = Role::firstOrCreate(['name' => 'deletable-role']);

        $response = $this->actingAs($this->getAdmin())
            ->delete("/admin/roles/{$role->id}");

        $response->assertRedirect('/admin/roles');
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_admin_cannot_delete_admin_role(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        $response = $this->actingAs($this->getAdmin())
            ->delete("/admin/roles/{$adminRole->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('roles', ['name' => 'admin']);
    }

    public function test_admin_can_assign_permissions_to_role(): void
    {
        $role = Role::firstOrCreate(['name' => 'perm-test-role']);
        $permission = Permission::firstOrCreate(['name' => 'users.create']);

        $response = $this->actingAs($this->getAdmin())
            ->put("/admin/roles/{$role->id}", [
                'name' => 'perm-test-role',
                'permissions' => ['users.create'],
            ]);

        $response->assertRedirect('/admin/roles');

        $this->assertTrue($role->fresh()->hasPermissionTo('users.create'));
    }
}
