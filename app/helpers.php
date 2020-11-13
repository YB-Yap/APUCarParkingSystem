<?php

use App\models\Config;
use App\Models\Parking;
use App\Models\Subscription;

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

if (!function_exists('getCarParkSize')) {
    function getCarParkSize()
    {
        return Config::carParkSize()->value;
    }
}

if (!function_exists('getSubsAvailability')) {
    function getSubsAvailability()
    {
        $availability = 0;

        // get subscription size
        $subscription_size = Config::subscriptionQuantity()->value;
        $subbed_amount = Subscription::activeSubsCount();

        // subscription is not full
        if ($subbed_amount < $subscription_size) {
            $availability = $subscription_size - $subbed_amount;
        }

        return $availability;
    }
}

if (!function_exists('getSubsSize')) {
    function getSubsSize()
    {
        return Config::subscriptionQuantity()->value;
    }
}

if (!function_exists('getSubsPrice')) {
    function getSubsPrice()
    {
        return Config::subscriptionPrice()->value;
    }
}

if (!function_exists('inRange')) {
    function inRange($value, $min, $max)
    {
        return ($min < $value && $value <= $max);
    }
}


if (!function_exists('estimateSubsRestockDate')) {
    function estimateSubsRestockDate()
    {

    }
}
