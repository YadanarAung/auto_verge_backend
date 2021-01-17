<?php

namespace App\Repositories;

use App\Customer;

class CustomerRepository
{
    public function __construct(Customer $customer) {
        $this->customer = $customer;
    }
    public function all() {
        return $this->customer->all();
    }

    public function show($column, $value) {
        return $this->customer
                    ->where($column, $value)
                    ->first();
    }

    public function create($payload) {
        return $this->customer
                    ->create($payload);
    }

    public function update($id, $payload) {
        return $this->customer
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->customer->destroy($id);
    }
}