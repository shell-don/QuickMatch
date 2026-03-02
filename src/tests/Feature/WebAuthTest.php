<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_via_web(): void
    {
        $response = $this->post('/register', [
            'name' => 'Web User',
            'email' => 'web@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'web@example.com',
            'name' => 'Web User',
        ]);
    }

    public function test_user_can_login_via_web(): void
    {
        $user = User::factory()->create([
            'email' => 'web-login@example.com',
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => 'web-login@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'email' => 'wrong-pass@example.com',
            'password' => 'correct-password',
        ]);

        $response = $this->post('/login', [
            'email' => 'wrong-pass@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertFalse(auth()->check());
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_user_can_logout_via_web(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/logout');

        $response->assertRedirect('/');
        $this->assertFalse(auth()->check());
    }

    public function test_password_not_exposed_in_registration_errors(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test',
            'email' => 'not-an-email',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
    }
}
