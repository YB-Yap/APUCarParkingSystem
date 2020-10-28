<?php

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'fullname' => 'admin',
            'email' => 'admin@staffmail.apu.edu.my',
            'password' => '$2y$10$8YJTM6c3pJ2yZmuRKps0f.sONdOoRXd9y7H372pkhYjecvlaK6vn6', // 123123
            'role' => 'admin',
            'apcard_number' => '000001',
            'apcard_balance' => 99999
        ]);
    }
}
