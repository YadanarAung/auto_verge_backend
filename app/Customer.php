<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [
        'created_at', 'updated_at', 
    ];

    public function bookings()
    {
        return $this->hasMany(Bookings::class);
    }
}
