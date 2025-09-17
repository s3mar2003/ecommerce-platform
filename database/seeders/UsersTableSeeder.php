<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
            'phone'=>'77777777777',
            'address'=>'tiaz'
        ]);
         User::factory()->count(2)->create(['role' => 'vendor']);

          User::factory()->count(2)->create(['role' => 'customer']);
    }
}
