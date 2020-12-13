<?php

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder_add_2020_12_13 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::where('fullname', 'Tang Chee Foong')->exists()) {
            return;
        }

        // insert Students
        $users = array(
            [
                'fullname' => 'Tang Chee Foong',
                'tp_number' => 'TP045902',
                'email' => 'tp045902@mail.apu.edu.my',
                'password' => '$2y$10$8YJTM6c3pJ2yZmuRKps0f.sONdOoRXd9y7H372pkhYjecvlaK6vn6', // 123123
                'role' => 'student',
                'apcard_number' => '045902',
                'apcard_balance' => 5000,
                'profile_pic_path' => '/images/avatar.png'
            ],
            [
                'fullname' => 'Wong Yi Xuan',
                'tp_number' => 'TP046347',
                'email' => 'tp046347@mail.apu.edu.my',
                'password' => '$2y$10$8YJTM6c3pJ2yZmuRKps0f.sONdOoRXd9y7H372pkhYjecvlaK6vn6', // 123123
                'role' => 'student',
                'apcard_number' => '046347',
                'apcard_balance' => 5000,
                'profile_pic_path' => '/images/avatar.png'
            ],
            [
                'fullname' => 'Wong Wei Syuen',
                'tp_number' => 'TP049739',
                'email' => 'tp049739@mail.apu.edu.my',
                'password' => '$2y$10$8YJTM6c3pJ2yZmuRKps0f.sONdOoRXd9y7H372pkhYjecvlaK6vn6', // 123123
                'role' => 'student',
                'apcard_number' => '049739',
                'apcard_balance' => 5000,
                'profile_pic_path' => '/images/avatar.png'
            ],
        );

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
