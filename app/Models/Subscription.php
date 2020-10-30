<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'valid_at', 'valid_till', 'is_active'];

    // laravel built-in function to append attributes
    // that are not saved in the database
    // but can be retrieve and return to the application
    protected $appends = ['is_expired'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // to get appended attribute
    public function getIsExpiredAttribute()
    {
        $today = Carbon::now();

        // check if today > valid_till

        return $this->attributes['is_expried'] = 'do something (replace me)';
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
}
