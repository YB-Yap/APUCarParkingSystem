<?php

/*
| Description: Used for defining relationship between models and other methods
*/

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parking extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'parking_zone', 'time_in', 'time_out', 'duration', 'fee', 'is_car_park_full'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeParkedAmountZoneA($query)
    {
        $today = Carbon::now()->toDateString();

        return $query->whereDate('time_in', $today)->whereNull('time_out')->where('parking_zone', 'A')->count();
    }

    public function scopeParkedAmountZoneB($query)
    {
        $today = Carbon::now()->toDateString();

        return $query->whereDate('time_in', $today)->whereNull('time_out')->where('parking_zone', 'B')->count();
    }

    public function scopeParkedVehicles($query)
    {
        $today = Carbon::now()->toDateString();

        return $query->whereDate('time_in', $today)->get();
    }
}
