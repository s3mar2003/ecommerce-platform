<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             
        'vendor_id' => User::where('role','vendor')->inRandomOrder()->first()->id ?? 1,
        'name' => $this->faker->word,
        'description' => $this->faker->sentence,
        'price' => $this->faker->randomFloat(2, 10, 500),
        'stock' => $this->faker->numberBetween(1,100),
        'image_url' => $this->faker->imageUrl(400,300,'product',true),
        'status' => 'active',
    ];
        
    }
}
