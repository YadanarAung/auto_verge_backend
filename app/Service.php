<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [
        'created_at', 'updated_at', 
    ];

    public function ngaSaYa()
    {
        return $this->hasOne(Ngasaya::class, 'appointment_client_id', 'appointment_client_id' );
    }

    public function bookingServices()
    {
        return $this->hasMany(BookingService::class);
    }
}
