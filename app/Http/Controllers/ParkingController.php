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
    private $FIRST_HOUR = 1;
    private $SECOND_HOUR = 2;
    private $SUBSEQUENT_HOUR = 0.5;
    private $ZONE_B_MAX = 4.7;
    private $ZONE_A = 5;

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
        $availability = 0;

        // get car park size
        $carpark_size = Config::carParkSize()->value;
        $parked_amount = Parking::parkedAmount();

        if ($parked_amount < $carpark_size) {
            $availability = $carpark_size - $parked_amount;
        }

        return response()->json($availability, 200);
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
        // $now = Carbon::now();
        // $user_id = Auth::user()->id;
        // $parking_zone = $request->parking_zone;

        $parking = new Parking();
        $parking->user_id = Auth::user()->id;
        $parking->parking_zone = $request->parking_zone;
        $parking->time_in = Carbon::now();
        $parking->save();

        return response()->json(['message' => 'Entered']);
    }

    public function exitCarPark(Request $request)
    {
        $now = Carbon::now();
        $user_id = Auth::user()->id;

        // $has_subscription = Subscription .....;

        $parking = Parking::where('user_id', $user_id)->latest('updated_at')->first();

        // dd($parking, $now->floatDiffInMinutes($parking->time_in), $now->floatDiffInHours($parking->time_in));
        $parking->time_out = $now;
        $parking->duration = round($now->floatDiffInHours($parking->time_in), 3);
        // $parking->fee = ......;
        $parking->update();

        // deduct apcard balance

        return response()->json(['message' => 'Exit']);
    }
}
