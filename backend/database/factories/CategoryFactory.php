<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $names = ['Light', 'Fragile', 'Heavy'];

        return [
            'name' => $this->faker->randomElement($names),
        ];
    }

}
