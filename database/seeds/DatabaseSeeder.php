<?php

/*
| Description: To define which seeder class to be called
*/

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(ConfigSeeder::class);
        $this->call(UserSeeder_add_2020_12_13::class);
    }
}
