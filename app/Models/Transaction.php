<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'type', 'category', 'amount', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeTypeSubs($query)
    {
        return $query->where('category', 'subscription');
    }

    public function scopeTypeParking($query)
    {
        return $query->where('category', 'parking');
    }
}
