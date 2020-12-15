<?php

/*
| Description: Used for inserting data to the database
*/

use Illuminate\Database\Seeder;
use App\Models\Config;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Config::where('key', 'subscription_price')->exists()) {
            return;
        }

        // insert Config
        $configs = array(
            [
                'key' => 'subscription_price',
                'value' => '6000'            // in cents
            ],
            [
                'key' => 'subscription_quantity',
                'value' => '5'
            ],
            [
                'key' => 'zone_a_size',    // for simulation purpose
                'value' => '10'
            ],
            [
                'key' => 'zone_b_size',    // for simulation purpose
                'value' => '10'
            ],
        );

        foreach ($configs as $config) {
            Config::create($config);
        }
    }
}
