<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    protected $guarded = [
        'created_at', 'updated_at', 
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
