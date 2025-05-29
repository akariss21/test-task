<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(100, 1000),
            'description' => $this->faker->sentence(),
            'user_id' => \App\Models\User::factory(),
            'category_id' => Category::factory(), 
            'quantity' => $this->faker->numberBetween(1, 20),
        ];
    }
}
