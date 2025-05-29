<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_become_seller()
    {
        $user = User::factory()->create(['role' => 'customer']);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/user/become-seller');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Теперь вы продавец']);

        $this->assertEquals('seller', $user->fresh()->role);
    }

    public function test_user_cannot_become_seller_twice()
    {
        $user = User::factory()->create(['role' => 'seller']);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/user/become-seller');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Вы уже продавец']);

        $this->assertEquals('seller', $user->fresh()->role);
    }

    public function test_user_can_get_their_role()
    {
        $user = User::factory()->create(['role' => 'customer']);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                        ->getJson('/api/user/role');

        $response->assertStatus(200)
                ->assertJsonPath('data.role', 'customer')
                ->assertJsonPath('data.is_seller', false);
    }
}
