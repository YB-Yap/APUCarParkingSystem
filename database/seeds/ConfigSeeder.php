<?php

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
        // insert Config
        $configs = array(
            [
                'key' => 'subscription_price',
                'value' => '600'            // in cents
            ],
            [
                'key' => 'subscription_quantity',
                'value' => '5'
            ],
            [
                'key' => 'carpark_size',    // for simulation purpose
                'value' => '10'
            ],
        );

        foreach ($configs as $config) {
            Config::create($config);
        }
    }
}
