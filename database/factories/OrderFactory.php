<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   
    protected $model = Order::class;
    public function definition()
    {
        return [
             'customer_id' => User::factory()->state(['role' => 'customer']),
             'total_amount' => $this->faker->randomFloat(2, 10, 500),
             'payment_status' => $this->faker->randomElement(['pending','paid','failed']),
             'order_status' => $this->faker->randomElement(['pending','processing','shipped','delivered','cancelled']),

        ];
    }
    }
    


