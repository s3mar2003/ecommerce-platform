<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $customers = User::where('role','customer')->get();

        foreach ($customers as $customer) {
            Order::factory()->count(rand(1,3))->create([
                'customer_id' => $customer->id
            ]);
        }
    }
}
