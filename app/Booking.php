<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [
        'created_at', 'updated_at', 
    ];

    public function bookingServices()
    {
        return $this->belongsToMany(Service::class, 'booking_services');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
