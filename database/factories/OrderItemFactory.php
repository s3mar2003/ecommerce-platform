<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'vendor_id' => User::factory()->state(['role' => 'vendor']),
            'quantity' => $this->faker->numberBetween(1,5),
            'price' => $this->faker->randomFloat(2,5,500),
        ];
    }
}
