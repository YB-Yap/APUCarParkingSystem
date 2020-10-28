<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeasonParking extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'starts_at', 'expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
