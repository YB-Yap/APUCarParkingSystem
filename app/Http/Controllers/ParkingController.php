<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParkingResource;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Config;
use App\Models\Parking;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParkingController extends Controller
{
    // fee in cents
    private $FEE_FIRST_HOUR = 159;          // 1st hour
    private $FEE_SECOND_HOUR = 265;         // 2nd hour
    private $FEE_THIRD_HOUR = 371;          // 3rd hour
    private $FEE_SUBSEQUENT_HOUR = 106;     // each hour for 2nd to 4th hour
    private $FEE_ZONE_B_MAX = 477;          // max fee per day - also the 4th hour
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

    public function getSize()
    {
        return response()->json(getCarParkSize(), 200);
    }

    public function getCarState()
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();
        $is_in_parking = false;
        $has_parked_today = false;
        $user_car = $user->parking()->whereDate('time_in', $today)->latest('updated_at')->get();

        if (!$user_car->isEmpty()) {
            if (!isset($user_car[0]->time_out)) {
                $is_in_parking = true;
            }
            if (count($user_car) > 1) {
                $has_parked_today = true;
            }
        }

        return response()->json([
            'isInParking' => $is_in_parking,
            'hasParkedToday' => $has_parked_today,
            'data' => ParkingResource::collection($user_car)
        ], 200);
    }

    public function enterCarPark(Request $request)
    {
        $now = Carbon::now();
        $user = Auth::user();
        $parking_zone = $request->parking_zone;
        $is_car_park_full = (getParkingAvailability() == 0);

        $parking = new Parking();
        $parking->user_id = $user->id;
        $parking->parking_zone = $parking_zone;
        $parking->time_in = $now;
        $parking->is_car_park_full = $is_car_park_full;
        $parking->save();

        return response()->json(['message' => 'Entered']);
    }

    public function exitCarPark(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $parking = $user->parking()->latest('updated_at')->first();

        $calc = $this->feeCalculation($user, $parking, $now);

        // check if enough balance
        if ($user->apcard_balance < $calc->to_pay) {
            return response()->json(['message' => 'Insufficient fund.', 'to_pay' => $calc->to_pay, 'isSuccess' => false]);
        }
        // deduct apcard balance
        $user->apcard_balance = $user->apcard_balance - $calc->to_pay;
        $user->update();

        // update parking record
        $parking->time_out = $now;
        $parking->duration = $calc->current_duration;
        $parking->fee = $calc->to_pay;
        $parking->update();

        return response()->json(['message' => 'Exit', 'isSuccess' => true]);
    }

    public function estimateFee()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $parking = $user->parking()->latest('updated_at')->first();

        $calc = $this->feeCalculation($user, $parking, $now);

        return response()->json($calc->to_pay, 200);
    }

    public function feeCalculation(User $_user, Parking $_parking, Carbon $_now)
    {
        $previous_paid = 0;
        $previous_duration = 0;
        $current_duration = round($_now->floatDiffInHours($_parking->time_in), 3);
        $total_duration = 0;
        $to_pay = 0;
        $is_car_park_full = (getParkingAvailability() == 0);
        $has_subscription = ($_user->subscription()->activeSubsCount() == 1);

        // car park is not full OR car park is full but duration exceeded 15 minutes
        if (!$is_car_park_full || $current_duration > $this->FOC_HOUR) {
            // check if there is other parking record on that day in the same parking zone
            $today_records = $_user->parking()
                            ->whereDate('time_out', $_now->toDateString())
                            ->whereNotNull('time_out')
                            ->where('parking_zone', $_parking->parking_zone)
                            ->latest('updated_at')
                            ->get();

            if ($_parking->parking_zone == 'B') {
                // if no subscription
                if (!$has_subscription) {
                    if ($today_records->count() > 0) {
                        // add previous fee to get remaining payable amount
                        foreach ($today_records as $index => $record) {
                            // add up all previous records on the day
                            // if the car park is not full and the fee is not 0
                            if (!$record->is_car_park_full || $record->fee > 0) {
                                $previous_duration += $record->duration;
                                $previous_paid += $record->fee;
                            }
                            // else -> FOC while the car park is full - does not add up
                        }
                        $total_duration = $previous_duration + $current_duration;
                        // check hour range
                        if (inRange($total_duration, 0, 1)) {
                            // first hour
                            $to_pay = $this->FEE_FIRST_HOUR - $previous_paid;
                        } else if (inRange($total_duration, 1, 2)) {
                            // second hour
                            $to_pay = $this->FEE_SECOND_HOUR - $previous_paid;
                        } else if (inRange($total_duration, 2, 3)) {
                            // third hour
                            $to_pay = $this->FEE_THIRD_HOUR - $previous_paid;
                        } else {
                            // fourth hour or more
                            $to_pay = $this->FEE_ZONE_B_MAX - $previous_paid;
                        }
                    } else {
                        // parking zone b - first time
                        // check hour range
                        if (inRange($current_duration, 0, 1)) {
                            // first hour
                            $to_pay = $this->FEE_FIRST_HOUR;
                        } else if (inRange($current_duration, 1, 2)) {
                            // second hour
                            $to_pay = $this->FEE_SECOND_HOUR;
                        } else if (inRange($current_duration, 2, 3)) {
                            // third hour
                            $to_pay = $this->FEE_THIRD_HOUR;
                        } else {
                            // fourth hour or more
                            $to_pay = $this->FEE_ZONE_B_MAX;
                        }
                    }
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

        return toJson([
            'current_duration' => $current_duration,
            'to_pay' => $to_pay,
        ]);
    }
}
