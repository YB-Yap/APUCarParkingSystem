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
        // insert Admin
        User::create([
            'fullname' => 'admin',
            'email' => 'admin@staffmail.apu.edu.my',
            'password' => '$2y$10$8YJTM6c3pJ2yZmuRKps0f.sONdOoRXd9y7H372pkhYjecvlaK6vn6', // 123123
            'role' => 'admin',
            'apcard_number' => '000001',
            'apcard_balance' => 99999,
            'profile_pic_path' => '/images/avatar-2.png'
        ]);

        // insert Students
        $users = array(
            [
                'fullname' => 'Yap Bo Yang',
                'tp_number' => 'TP049727',
                'email' => 'tp049727@mail.apu.edu.my',
                'password' => '$2y$10$8YJTM6c3pJ2yZmuRKps0f.sONdOoRXd9y7H372pkhYjecvlaK6vn6', // 123123
                'role' => 'student',
                'apcard_number' => '049727',
                'apcard_balance' => 5000,
                'profile_pic_path' => '/images/my-avatar.png'
            ],
            [
                'fullname' => 'Low Chi Haw',
                'tp_number' => 'TP049318',
                'email' => 'tp049318@mail.apu.edu.my',
                'password' => '$2y$10$8YJTM6c3pJ2yZmuRKps0f.sONdOoRXd9y7H372pkhYjecvlaK6vn6', // 123123
                'role' => 'student',
                'apcard_number' => '049318',
                'apcard_balance' => 1000,
                'profile_pic_path' => '/images/avatar.png'
            ],
            [
                'fullname' => 'Loo Ying Chi',
                'tp_number' => 'TP049162',
                'email' => 'tp049162@mail.apu.edu.my',
                'password' => '$2y$10$8YJTM6c3pJ2yZmuRKps0f.sONdOoRXd9y7H372pkhYjecvlaK6vn6', // 123123
                'role' => 'student',
                'apcard_number' => '049162',
                'apcard_balance' => 2000,
                'profile_pic_path' => '/images/avatar.png'
            ],
        );

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
