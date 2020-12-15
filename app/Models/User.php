<?php

/*
| Description: Used for defining relationship between models and other methods
*/

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fullname', 'tp_number', 'email', 'password', 'role', 'apcard_number', 'apcard_balance'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    public function parking()
    {
        return $this->hasMany(Parking::class);
    }

    public function subscription()
    {
        return $this->hasMany(Subscription::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
