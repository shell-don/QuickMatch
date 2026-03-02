<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Total Utilisateurs');
        $this->assertNotEmpty($response->getContent());
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $user = User::factory()->create();
        $user->assignRole($userRole);

        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_shows_correct_stats(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        User::factory()->count(5)->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('6');
        $this->assertNotEmpty($response->getContent());
    }
}
