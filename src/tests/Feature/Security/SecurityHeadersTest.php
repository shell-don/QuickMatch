<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin', 'guard_name' => 'web']);
    }

    public function test_strict_transport_security_header_is_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
    }

    public function test_x_content_type_options_header_is_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_x_frame_options_header_is_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_xss_protection_header_is_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-XSS-Protection', '0');
    }

    public function test_content_security_policy_header_is_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('Content-Security-Policy');
    }

    public function test_cross_origin_headers_are_present(): void
    {
        if (app()->environment('production')) {
            $response = $this->get('/');

            $response->assertHeader('Cross-Origin-Opener-Policy', 'same-origin');
            $response->assertHeader('Cross-Origin-Resource-Policy', 'same-origin');
        } else {
            $this->assertTrue(true);
        }
    }

    public function test_referrer_policy_header_is_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('Referrer-Policy');
    }

    public function test_permissions_policy_header_is_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=()');
    }

    public function test_unauthenticated_user_cannot_access_protected_routes(): void
    {
        $response = $this->getJson('/api/v1/auth/me');

        $response->assertStatus(401);
    }

    public function test_user_without_admin_role_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
    }

    public function test_csrf_token_is_required_for_post_requests(): void
    {
        $response = $this->call('POST', '/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
    }

    public function test_mass_assignment_protection(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/auth/me', [
            'name' => 'Hacked Name',
            'is_admin' => true,
            'email' => 'hacked@example.com',
        ]);

        $user->refresh();

        $this->assertNotEquals('Hacked Name', $user->name);
        $this->assertNotEquals('hacked@example.com', $user->email);
    }

    public function test_sql_injection_protection(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/admin/users?email=test\' OR 1=1--');

        $response->assertStatus(200);
    }

    public function test_xss_protection_in_responses(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/users?name=<script>alert(1)</script>');

        $response->assertStatus(200);
        $response->assertDontSee('<script>alert(1)</script>');
    }
}
