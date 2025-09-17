<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
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
           'method' => $this->faker->randomElement(['cash','card','paypal']),
           'amount' => $this->faker->randomFloat(2, 10, 500),
           'status' => $this->faker->randomElement(['pending','completed','failed']),
        ];
    }
}
