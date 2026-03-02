<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminProfileTest extends TestCase
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

    public function test_admin_can_view_profile(): void
    {
        $response = $this->actingAs($this->getAdmin())
            ->get('/admin/profile');

        $response->assertStatus(200);
        $response->assertSee('Mon profil');
    }

    public function test_admin_can_update_profile_name(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)
            ->put('/admin/profile', [
                'name' => 'Updated Name',
                'email' => $admin->email,
            ]);

        $response->assertSessionHas('success');

        $this->assertEquals('Updated Name', $admin->fresh()->name);
    }

    public function test_admin_can_update_profile_email(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)
            ->put('/admin/profile', [
                'name' => $admin->name,
                'email' => 'newemail@example.com',
            ]);

        $response->assertSessionHas('success');

        $this->assertEquals('newemail@example.com', $admin->fresh()->email);
    }

    public function test_admin_can_change_password_with_correct_current_password(): void
    {
        $admin = User::factory()->create([
            'password' => bcrypt('old-password'),
        ]);
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)
            ->put('/admin/profile', [
                'name' => $admin->name,
                'email' => $admin->email,
                'current_password' => 'old-password',
                'password' => 'new-password123',
                'password_confirmation' => 'new-password123',
            ]);

        $response->assertSessionHas('success');

        $this->assertTrue(password_verify('new-password123', $admin->fresh()->password));
    }

    public function test_admin_cannot_change_password_without_current_password(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)
            ->put('/admin/profile', [
                'name' => $admin->name,
                'email' => $admin->email,
                'password' => 'new-password123',
                'password_confirmation' => 'new-password123',
            ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_admin_cannot_change_password_with_wrong_current_password(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)
            ->put('/admin/profile', [
                'name' => $admin->name,
                'email' => $admin->email,
                'current_password' => 'wrong-password',
                'password' => 'new-password123',
                'password_confirmation' => 'new-password123',
            ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_admin_cannot_update_profile_with_invalid_email(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)
            ->put('/admin/profile', [
                'name' => $admin->name,
                'email' => 'not-an-email',
            ]);

        $response->assertSessionHasErrors('email');
    }
}
