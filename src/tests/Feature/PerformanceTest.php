<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_dashboard_loads_under_100ms(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $start = microtime(true);

        $response = $this->actingAs($admin)
            ->get('/admin/dashboard');

        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);
        $this->assertNotEmpty($response->getContent());
        $this->assertLessThan(100, $duration, "Dashboard took {$duration}ms, expected less than 100ms");
    }

    public function test_user_list_pagination_under_100ms(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        User::factory()->count(20)->create();

        $start = microtime(true);

        $response = $this->actingAs($admin)
            ->get('/admin/users');

        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);

        $this->assertLessThan(100, $duration, "User list took {$duration}ms, expected less than 100ms");
    }

    public function test_api_login_response_under_100ms(): void
    {
        $user = User::factory()->create([
            'password' => 'password123',
        ]);

        $start = microtime(true);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);

        $this->assertLessThan(100, $duration, "API login took {$duration}ms, expected less than 100ms");
    }

    public function test_api_registration_response_under_100ms(): void
    {
        $start = microtime(true);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Perf Test User',
            'email' => 'perf@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(201);

        $this->assertLessThan(100, $duration, "API registration took {$duration}ms, expected less than 100ms");
    }

    public function test_web_dashboard_loads_under_100ms(): void
    {
        $user = User::factory()->create();

        $start = microtime(true);

        $response = $this->actingAs($user)
            ->get('/dashboard');

        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);

        $this->assertLessThan(100, $duration, "Web dashboard took {$duration}ms, expected less than 100ms");
    }

    public function test_role_list_loads_under_100ms(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $start = microtime(true);

        $response = $this->actingAs($admin)
            ->get('/admin/roles');

        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);

        $this->assertLessThan(100, $duration, "Role list took {$duration}ms, expected less than 100ms");
    }

    public function test_api_authenticated_request_under_100ms(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $start = microtime(true);

        $response = $this->withToken($token->plainTextToken)
            ->getJson('/api/v1/auth/me');

        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);

        $this->assertLessThan(100, $duration, "API authenticated request took {$duration}ms, expected less than 100ms");
    }
}
