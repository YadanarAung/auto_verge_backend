<?php

namespace App\Repositories;

use App\BookingService;

class BookingServiceRepository
{
    public function __construct(BookingService $bookingService) {
        $this->bookingService = $bookingService;
    }
    public function all() {
        return $this->bookingService->all();
    }

    public function show($column, $value) {
        return $this->bookingService
                    ->where($column, $value)
                    ->first();
    }

    public function create($payload) {
        return $this->bookingService
                    ->create($payload);
    }
}