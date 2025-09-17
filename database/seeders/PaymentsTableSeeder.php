<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;



class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $orders = Order::all();

        foreach ($orders as $order) {
            Payment::factory()->create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'method' => ['credit_card','paypal','cash_on_delivery'][rand(0,2)],
                'status' => ['pending','paid','failed'][rand(0,2)]
            ]);
        }
    }
}
