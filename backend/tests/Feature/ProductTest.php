<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_can_create_product()
    {
        $user = User::factory()->create(['role' => 'seller']);
        $token = JWTAuth::fromUser($user);
        $category = $category = Category::firstOrCreate(['name' => 'Light']);

        $data = [
            'name' => 'Test Product',
            'price' => 100,
            'description' => 'Test Description',
            'quantity' => 10,
            'category_id' => $category->id
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/products', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Test Product']);

        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_customer_cannot_create_product()
    {
        $user = User::factory()->create(['role' => 'customer']);
        $token = JWTAuth::fromUser($user);
        $category = Category::firstOrCreate(['name' => 'Light']);

        $data = [
            'name' => 'Invalid Product',
            'price' => 100,
            'description' => 'Fail',
            'quantity' => 5,
            'category_id' => $category->id
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/products', $data);

        $response->assertStatus(403)
                 ->assertJson(['message' => 'Станьте продавцом, чтобы добавлять товары']);
    }

    public function test_product_can_be_updated()
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $token = JWTAuth::fromUser($seller);

        $product = Product::factory()->create(['user_id' => $seller->id]);

        $updatePayload = [
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 199.99,
            'quantity' => 20,
            'category_id' => $product->category_id,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
                        ->patchJson("/api/products/{$product->id}", $updatePayload);

        $response->assertStatus(200)
                ->assertJsonFragment(['name' => 'Updated Product']);
    }


    public function test_product_can_be_deleted()
    {
        $user = User::factory()->create(['role' => 'seller']);
        $token = JWTAuth::fromUser($user);
        $product = Product::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
