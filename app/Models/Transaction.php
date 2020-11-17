<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'type', 'amount', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
