<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BalanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_balance()
    {
        $user = User::factory()->create(['balance' => 500]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->getJson('/api/balance');

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'balance' => '500.00',
                     ]
                 ]);
    }

    public function test_guest_cannot_view_balance()
    {
        $response = $this->getJson('/api/balance');

        $response->assertStatus(401);
    }
}
