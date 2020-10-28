<?php

namespace App\Models;

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
}
