<?php

namespace App\Repositories;

use App\Booking;

class BookingRepository
{
    public function __construct(Booking $booking) {
        $this->booking = $booking;
    }
    public function all() {
        return $this->booking->with(['customer', 'bookingServices'])->get();
    }

    public function show($column, $value) {
        return $this->booking
                    ->where($column, $value)
                    ->first();
    }

    public function create($payload) {
        return $this->booking
                    ->create($payload);
    }

    public function update($id, $payload) {
        return $this->booking
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->booking->destroy($id);
    }

    public function getMaxInvoiceNo(){
        return $this->booking
                    ->max('invoice_no');
    }
}