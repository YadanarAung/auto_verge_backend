<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
    
        $services = $this->bookingServices->pluck('name')->toArray();
        
        return [
            'id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'car_no' => $this->car_no,
            'additional_services' => implode(',', $services),
            'booking_date' => $this->booking_date,
            'payment_amount' => $this->payment_amount,
            'duration' => $this->duration,
            'note' => $this->note,
        ];
    }
}
