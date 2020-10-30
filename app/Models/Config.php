<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = ['key', 'value'];

    // creating local scope
    // Local Scopes allow developer to define common sets of constraints
    // that may be reused throughout the application
    // without query with many where clause

    public function scopeSubscriptionPrice($query)
    {
        return $query->where('key', 'subscription_price')->first();
    }

    public function scopeSubscriptionQuantity($query)
    {
        return $query->where('key', 'subscription_quantity')->first();
    }

    public function scopeCarparkSize($query)
    {
        return $query->where('key', 'carpark_size')->first();
    }
}
