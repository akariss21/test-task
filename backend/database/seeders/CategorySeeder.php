<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = ['Light', 'Fragile', 'Heavy'];
        foreach ($categories as $category) {
            Category::create(['name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
                ]);
        }
    }
}
