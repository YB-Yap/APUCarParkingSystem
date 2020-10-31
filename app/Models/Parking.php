<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parking extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'parking_zone', 'time_in', 'time_out', 'duration'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeParkedAmount($query)
    {
        $today = Carbon::now()->toDateString();

        return $query->whereDate('time_in', $today)->count();
    }
}
