<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Запускаем сидер категорий перед каждым тестом
        $this->seed(\Database\Seeders\CategorySeeder::class);
    }

    public function test_can_get_list_of_categories()
    {
        $user = User::factory()->create();
        $token = auth()->login($user); // JWT токен

        $response = $this->withHeader('Authorization', "Bearer $token")
                        ->getJson('/api/categories');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }
}
