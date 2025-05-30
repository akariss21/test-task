<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create(['role' => 'customer'])->id,
            'customer_name' => $this->faker->name,
            'status' => 'new',
            'order_date' => now(),
        ];
    }
}
