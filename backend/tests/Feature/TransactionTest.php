<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_deposit_balance()
    {
        $user = User::factory()->create(['balance' => 0]);
        $token = JWTAuth::fromUser($user);;

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/transactions/deposit', ['amount' => 500]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Баланс пополнен на 500',
                     'balance' => 500,
                 ]);

        $this->assertEquals(500, $user->fresh()->balance);
    }

    public function test_user_can_withdraw_balance()
    {
        $user = User::factory()->create(['balance' => 1000]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/transactions/withdraw', ['amount' => 400]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Вывод произведен',
                     'balance' => 600,
                 ]);

        $this->assertEquals(600, $user->fresh()->balance);
    }

    public function test_user_cannot_withdraw_more_than_balance()
    {
        $user = User::factory()->create(['balance' => 100]);
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/transactions/withdraw', ['amount' => 200]);

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Недостаточно средств']);
    }

    public function test_user_can_purchase_order()
    {
        $customer = User::factory()->create(['role' => 'customer', 'balance' => 1000]);
        $seller = User::factory()->create(['role' => 'seller', 'balance' => 0]);

        $product = Product::factory()->create([
            'price' => 200,
            'quantity' => 10,
            'user_id' => $seller->id,
        ]);

        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'status' => 'new',
        ]);

        $order->products()->attach($product->id, ['quantity' => 2]);

        $token = JWTAuth::fromUser($customer);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/transactions/purchase', ['order_id' => $order->id]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Покупка успешно завершена']);

        $this->assertEquals(1000 - 400, $customer->fresh()->balance); // 2 x 200
        $this->assertEquals(0 + 360, $seller->fresh()->balance);   // 90% от 400
        $this->assertEquals('completed', $order->fresh()->status);
        $this->assertEquals(8, $product->fresh()->quantity);
    }

    public function test_purchase_fails_if_not_enough_balance()
    {
        $customer = User::factory()->create(['role' => 'customer', 'balance' => 100]);
        $seller = User::factory()->create(['role' => 'seller']);

        $product = Product::factory()->create([
            'price' => 100,
            'quantity' => 1,
            'user_id' => $seller->id,
        ]);

        $order = Order::factory()->create(['user_id' => $customer->id]);
        $order->products()->attach($product->id, ['quantity' => 2]); // Нужно 200

        $token = JWTAuth::fromUser($customer);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/transactions/purchase', ['order_id' => $order->id]);

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Недостаточно средств']);
    }
}
