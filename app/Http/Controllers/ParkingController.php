<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParkingResource;
use Carbon\Carbon;
use App\Models\Config;
use App\Models\Parking;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParkingController extends Controller
{
    // fee in cents
    private $FEE_FIRST_HOUR = 159;          // 1 hour
    private $FEE_SUBSEQUENT_HOUR = 106;     // 3 hours -> 318 (to max)
    private $FEE_ZONE_B_MAX = 477;          // max fee per day
    private $ZONE_B_MAX_HOURS = 4;          // 4 hours to max the fee
    private $FEE_ZONE_A = 530;              // fixed fee per day
    private $FOC_HOUR = 0.25;               // 15 minutes free of charge if the parking is full

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function show(Parking $parking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function edit(Parking $parking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Parking $parking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parking $parking)
    {
        //
    }

    public function getAvailability()
    {
        return response()->json(getParkingAvailability(), 200);
    }

    public function getCarState()
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();

        $user_car = Parking::whereDate('time_in', $today)
                    ->where('user_id', $user->id)
                    ->latest('updated_at')
                    ->first();

        // isset() is a built in function to check if the variable is defined and is not null
        if (!isset($user_car) || isset($user_car->time_out)) {
            // no record for today / car is not any parking zone
            return response()->json([
                'isInParking' => false,
                'data' => isset($user_car) ? new ParkingResource($user_car) : null
            ], 200);
        } else {
            // has at least one record
            return response()->json([
                'isInParking' => true,
                'data' => new ParkingResource($user_car)
            ], 200);
        }
    }

    public function enterCarPark(Request $request)
    {
        $now = Carbon::now();
        $user_id = Auth::user()->id;
        $parking_zone = $request->parking_zone;

        $parking = new Parking();
        $parking->user_id = $user_id;
        $parking->parking_zone = $parking_zone;
        $parking->time_in = $now;
        $parking->save();

        return response()->json(['message' => 'Entered']);
    }

    public function exitCarPark(Request $request)
    {
        $now = Carbon::now();
        $user_id = Auth::user()->id;
        $previous_paid = 0;
        $previous_duration = 0;
        $today_duration = 0;
        $to_pay = 0;
        $is_car_park_full = (getParkingAvailability() == 0);

        $parking = Parking::where('user_id', $user_id)->latest('updated_at')->first();
        $today_duration = round($now->floatDiffInHours($parking->time_in), 3);

        // $has_subscription = Subscription .....;

        // car park is not full OR car park is full but duration exceeded 15 minutes
        if (!$is_car_park_full || $today_duration > $this->FOC_HOUR) {
            // check if there is other parking record on that day in the same parking zone
            $today_records = Parking::where('user_id', $user_id)
                            ->whereDate('time_out', $now->toDateString())
                            ->whereNotNull('time_out')
                            ->where('parking_zone', $parking->parking_zone)
                            ->latest('updated_at')
                            ->get();

            dd($today_records, new ParkingResource($today_records), $today_records->count());

            if ($parking->parking_zone == 'B') {
                // if no subscription

                if ($today_records->count() > 0) {
                    // add previous fee to get remaining payable amount
                    foreach ($today_records as $index => $record) {
                        // previous record is not FOC
                        if ($record->fee != 0) {
                            $previous_duration += $record->duration;
                            $previous_paid += $record->fee;
                        }
                        // else -> FOC - does not add up

                        // ...
                    }
                } else {
                    // parking zone b - first time

                }

                // else -> has subscription, FOC
            } else {
                // parking zone a - first time
                if ($today_records->count() == 0) {
                    $to_pay = $this->FEE_ZONE_A;
                }
                // else -> parked once on that day, does not charge more
            }
        }
        // else -> FOC - car park is full AND exit within 15 minutes

        dd(123123123);

        $parking->time_out = $now;
        $parking->duration = $today_duration;
        $parking->fee = $to_pay;
        $parking->update();

        // deduct apcard balance

        return response()->json(['message' => 'Exit']);
    }
}
