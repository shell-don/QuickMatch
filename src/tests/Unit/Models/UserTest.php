<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created_with_factory(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    public function test_user_has_correct_fillable_attributes(): void
    {
        $user = new User;

        $this->assertContains('name', $user->getFillable());
        $this->assertContains('email', $user->getFillable());
        $this->assertContains('password', $user->getFillable());
    }

    public function test_user_password_is_hashed(): void
    {
        $user = User::factory()->create([
            'password' => 'test-password',
        ]);

        $this->assertNotEquals('test-password', $user->password);
        $this->assertTrue(password_verify('test-password', $user->password));
    }

    public function test_user_has_api_token_capability(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('test-token');

        $this->assertNotNull($token->plainTextToken);
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'test-token',
        ]);
    }

    public function test_user_can_have_multiple_tokens(): void
    {
        $user = User::factory()->create();

        $token1 = $user->createToken('token-1');
        $token2 = $user->createToken('token-2');

        $this->assertCount(2, $user->tokens);
    }
}
