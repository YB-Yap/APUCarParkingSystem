<?php

/*
| Description: Used for defining relationship between models and other methods
*/

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

    public function scopeActiveSubsCount($query)
    {
        return $query->where('is_active', true)->count();
    }

    public function scopeActiveSubs($query)
    {
        return $query->where('is_active', true)->get();
    }

    public function scopeAllSubs($query)
    {
        return $query->where('is_expired', false)->get();
    }
}
