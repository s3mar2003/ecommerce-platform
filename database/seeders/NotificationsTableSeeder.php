<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Notification::factory()->count(rand(1,5))->create([
                'user_id' => $user->id,
                'type' => ['order','system','promo'][rand(0,2)],
                'status' => ['unread','read'][rand(0,1)]
            ]);
        }
    }
}
