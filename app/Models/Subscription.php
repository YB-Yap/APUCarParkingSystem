<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'valid_at', 'valid_till', 'is_active', 'is_expired'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // functions
    public function purchase()
    {
        // is_active does not reach the limit
        // create record

    }

    public function extend()
    {
        // valid_till + 1 month

    }

    public function terminate()
    {
        // is_active = false

    }

    public function scopeSubbedAmount($query)
    {
        return $query->where('is_active', true)->count();
    }

    public function scopeSubbedUsers($query)
    {
        return $query->where('is_active', true)->get();
    }
}
