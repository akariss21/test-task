<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsApi(User $user)
    {
        $token = JWTAuth::fromUser($user);
        return $this->withHeader('Authorization', "Bearer $token");
    }

    public function test_can_get_list_of_orders()
    {
        $user = User::factory()->create();

        $product = Product::factory()->create();
        $order = Order::factory()->create(['status' => 'new']);
        $order->products()->attach($product->id, ['quantity' => 2]);

        $response = $this->actingAsApi($user)->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'new'])
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'customer_name',
                        'order_date',
                        'status',
                        'comment',
                        'products' => [
                            '*' => ['id', 'name', 'price', 'quantity']
                        ]
                    ]
                ]
            ]);
    }

        public function test_can_create_order_with_valid_statuses()
    {
        $user = User::factory()->create();

        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        foreach (['new', 'completed', null] as $status) {
            $payload = [
                'customer_name' => 'John Doe',
                'order_date' => '2025-05-29',
                'status' => $status,
                'comment' => 'Test order',
                'products' => [
                    ['id' => $product1->id, 'quantity' => 1],
                    ['id' => $product2->id, 'quantity' => 3],
                ]
            ];

            if ($status === null) {
                unset($payload['status']);
            }

            $response = $this->actingAsApi($user)->postJson('/api/orders', $payload);


            $response->assertStatus(201)
                ->assertJsonFragment(['customer_name' => 'John Doe'])
                ->assertJsonCount(2, 'data.products');

            if ($status !== null) {
                $this->assertDatabaseHas('orders', ['customer_name' => 'John Doe', 'status' => $status]);
            } else {
                $this->assertDatabaseHas('orders', ['customer_name' => 'John Doe']);
            }
        }
    }

    public function test_cannot_create_order_with_invalid_status()
    {
        $user = User::factory()->create();

        $product = Product::factory()->create();

        $payload = [
            'customer_name' => 'Jane Doe',
            'order_date' => '2025-05-29',
            'status' => 'invalid_status',
            'products' => [
                ['id' => $product->id, 'quantity' => 1],
            ]
        ];

        $response = $this->actingAsApi($user)->postJson('/api/orders', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('status');
    }

    public function test_can_update_order_status_to_completed()
    {
        $user = User::factory()->create();

        $order = Order::factory()->create(['status' => 'new']);

        $response = $this->actingAsApi($user)->patchJson("/api/orders/{$order->id}/complete");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Success']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
        ]);
    }

    public function test_can_get_single_order_with_products()
    {
        $user = User::factory()->create();

        $product = Product::factory()->create();
        $order = Order::factory()->create(['status' => 'new']);
        $order->products()->attach($product->id, ['quantity' => 5]);

        $response = $this->actingAsApi($user)->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $order->id, 'status' => 'new'])
            ->assertJsonCount(1, 'data.products')
            ->assertJsonFragment(['quantity' => 5]);
    }
}
