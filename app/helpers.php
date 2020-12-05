<?php

use App\Models\Config;
use App\Models\Parking;
use App\Models\Subscription;
use Symfony\Component\Mime\Encoder\EncoderInterface;

if (!function_exists('toJson')) {
    function toJson($data)
    {
        return json_decode(json_encode($data));
    }
}

if (!function_exists('getParkingAvailability')) {
    function getParkingAvailability()
    {
        $availability = [
            'zone_a' => 0,
            'zone_b' => 0,
        ];

        // get car park size
        $zone_a_size = (int)Config::zoneASize()->value;
        $zone_b_size = (int)Config::zoneBSize()->value;

        // car park is not full
        $parked_amount = Parking::parkedAmountZoneA();
        if ($parked_amount < $zone_a_size) {
            $availability['zone_a'] = $zone_a_size - $parked_amount;
        }
        $parked_amount = Parking::parkedAmountZoneB();
        if ($parked_amount < $zone_b_size) {
            $availability['zone_b'] = $zone_b_size - $parked_amount;
        }

        return toJson($availability);
    }
}

if (!function_exists('getCarParkSize')) {
    function getCarParkSize()
    {
        return [
            'zone_a' => (int)Config::zoneASize()->value,
            'zone_b' => (int)Config::zoneBSize()->value,
        ];
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
