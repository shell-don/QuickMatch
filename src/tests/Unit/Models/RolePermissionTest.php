<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_assigned_role(): void
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'admin']);

        $user->assignRole($role);

        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_user_can_have_multiple_roles(): void
    {
        $user = User::factory()->create();
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);

        $user->assignRole([$adminRole, $managerRole]);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('manager'));
    }

    public function test_user_can_be_given_permission(): void
    {
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => 'users.view']);

        $user->givePermissionTo($permission);

        $this->assertTrue($user->hasPermissionTo('users.view'));
    }

    public function test_role_can_have_permissions(): void
    {
        $role = Role::firstOrCreate(['name' => 'test-role']);
        $permission = Permission::firstOrCreate(['name' => 'users.create']);

        $role->givePermissionTo($permission);

        $this->assertTrue($role->hasPermissionTo('users.create'));
    }

    public function test_user_inherits_permissions_from_role(): void
    {
        $role = Role::firstOrCreate(['name' => 'test-inherit']);
        $permission = Permission::firstOrCreate(['name' => 'users.delete']);
        $role->givePermissionTo($permission);

        $user = User::factory()->create();
        $user->assignRole($role);

        $this->assertTrue($user->hasPermissionTo('users.delete'));
    }

    public function test_user_can_check_permission_via_gate(): void
    {
        $user = User::factory()->create();
        $permission = Permission::firstOrCreate(['name' => 'profile.view']);
        $user->givePermissionTo($permission);

        $this->assertTrue($user->can('profile.view'));
        $this->assertFalse($user->can('users.delete'));
    }

    public function test_user_can_remove_role(): void
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'removable']);
        $user->assignRole($role);

        $user->removeRole($role);

        $this->assertFalse($user->hasRole('removable'));
    }

    public function test_user_can_sync_roles(): void
    {
        $user = User::factory()->create();
        $role1 = Role::firstOrCreate(['name' => 'sync-role-1']);
        $role2 = Role::firstOrCreate(['name' => 'sync-role-2']);

        $user->assignRole('sync-role-1');
        $user->syncRoles([$role2]);

        $this->assertFalse($user->hasRole('sync-role-1'));
        $this->assertTrue($user->hasRole('sync-role-2'));
    }
}
