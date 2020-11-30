<?php

namespace App\Console\Commands;

use App\Models\Parking;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ParkingCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:parking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check parking records on 0000h, update accordingly';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::today();
        $yesterday = Carbon::today()->subSecond(1);     // 23:59:59

        // get all parking records that the vehicle is still inside car park
        $parking_ids = Parking::whereDate('time_in', $yesterday->toDateString())
                        ->whereNull('time_out')->get(['id']);

        if ($parking_ids) {
            // loop all the ids for fee calculation and create new records for the next day
            foreach ($parking_ids as $pid) {
                $parking = Parking::find($pid->id);
                $user = User::find($parking->user_id);

                $calc = $this->feeCalculation($user, $parking, $yesterday);

                $parking->time_out = $yesterday;
                $parking->duration = $calc->current_duration;
                $parking->fee = $calc->to_pay;
                $parking->update();

                $this->deductBalance($user, $calc->to_pay);

                // create new record
                $new_parking = new Parking();
                $new_parking->user_id = $user->id;
                $new_parking->parking_zone = $parking->parking_zone;
                $new_parking->time_in = $today;
                $new_parking->save();
            }
        }

    }


    public function deductBalance(User $user, $amount)
    {
        // deduct apcard balance
        $user->apcard_balance = $user->apcard_balance - $amount;
        $user->update();

        // insert transaction
        $transaction = new Transaction();

        $transaction->user_id = $user->id;
        $transaction->type = "deduct";
        $transaction->category = "parking";
        $transaction->amount = $amount;
        $transaction->payment_method = "apcard";
        $transaction->description = "Parking fee";
        $transaction->save();
    }

    public function feeCalculation(User $_user, Parking $_parking, Carbon $_yesterday)
    {
        // variables
        $FEE_FIRST_HOUR = 159;          // 1st hour
        $FEE_SECOND_HOUR = 265;         // 2nd hour
        $FEE_THIRD_HOUR = 371;          // 3rd hour
        $FEE_SUBSEQUENT_HOUR = 106;     // each hour for 2nd to 4th hour
        $FEE_ZONE_B_MAX = 477;          // max fee per day - also the 4th hour
        $ZONE_B_MAX_HOURS = 4;          // 4 hours to max the fee
        $FEE_ZONE_A = 530;              // fixed fee per day
        $FOC_HOUR = 0.25;

        $previous_paid = 0;
        $previous_duration = 0;
        $total_duration = 0;
        // $is_car_park_full = (getParkingAvailability() == 0);
        $has_subscription = ($_user->subscription()->activeSubsCount() == 1);

        $data = toJson([
            'current_duration' => round($_yesterday->floatDiffInHours($_parking->time_in), 3),
            'to_pay' => 0,
        ]);

        // FOC - car park is full AND exit within 15 minutes
        if ($_parking->is_car_park_full && $data->current_duration > $FOC_HOUR) {
            return $data;
        }

        // check if there is other parking record on that day in the same parking zone
        $yesterday_records = $_user->parking()
                        ->whereDate('time_in', $_yesterday->toDateString())
                        ->whereNotNull('time_out')
                        ->where('parking_zone', $_parking->parking_zone)
                        ->latest('updated_at')
                        ->get();

        // parking zone A
        if ($_parking->parking_zone == 'A') {
            // count = 0 => first time on that day
            if ($yesterday_records->count() == 0) {
                $data->to_pay = $FEE_ZONE_A;
            }
            return $data;
        }

        // parking zone B
        // FOC - has subscription
        if ($has_subscription) {
            return $data;
        }

        if ($yesterday_records->count() > 0) {
            // add previous fee to get remaining payable amount
            foreach ($yesterday_records as $index => $record) {
                // add up all previous records on the day
                // if the car park is not full and the fee is not 0
                if (!$record->is_car_park_full || $record->fee > 0) {
                    $previous_duration += $record->duration;
                    $previous_paid += $record->fee;
                }
                // else -> FOC while the car park is full - does not add up
            }
            $total_duration = $previous_duration + $data->current_duration;
            // check hour range
            if (inRange($total_duration, 0, 1)) {
                // first hour
                $data->to_pay = $FEE_FIRST_HOUR - $previous_paid;
            } else if (inRange($total_duration, 1, 2)) {
                // second hour
                $data->to_pay = $FEE_SECOND_HOUR - $previous_paid;
            } else if (inRange($total_duration, 2, 3)) {
                // third hour
                $data->to_pay = $FEE_THIRD_HOUR - $previous_paid;
            } else {
                // fourth hour or more
                $data->to_pay = $FEE_ZONE_B_MAX - $previous_paid;
            }
        } else {
            // parking zone b - first time
            // check hour range
            if (inRange($data->current_duration, 0, 1)) {
                // first hour
                $data->to_pay = $FEE_FIRST_HOUR;
            } else if (inRange($data->current_duration, 1, 2)) {
                // second hour
                $data->to_pay = $FEE_SECOND_HOUR;
            } else if (inRange($data->current_duration, 2, 3)) {
                // third hour
                $data->to_pay = $FEE_THIRD_HOUR;
            } else {
                // fourth hour or more
                $data->to_pay = $FEE_ZONE_B_MAX;
            }
        }

        return $data;
    }
}
