<?php

use App\models\Config;
use App\Models\Parking;

if (!function_exists('getSubsAvailability')) {
    function getSubsAvailability()
    {
        // get available subscription
        // return count

    }
}


if (!function_exists('getParkingAvailability')) {
    function getParkingAvailability()
    {
        $availability = 0;

        // get car park size
        $carpark_size = Config::carParkSize()->value;
        $parked_amount = Parking::parkedAmount();

        // car park is not full
        if ($parked_amount < $carpark_size) {
            $availability = $carpark_size - $parked_amount;
        }

        return $availability;
    }
}


if (!function_exists('estimateSubsRestockDate')) {
    function estimateSubsRestockDate()
    {

    }
}
