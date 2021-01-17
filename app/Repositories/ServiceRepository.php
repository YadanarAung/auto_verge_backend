<?php

namespace App\Repositories;

use App\Service;

class serviceRepository
{
    public function __construct(Service $service) {
        $this->service = $service;
    }
    public function all() {
        return $this->service->all();
    }

    public function show($column, $value) {
        return $this->service
                    ->where($column, $value)
                    ->first();
    }

    public function create($payload) {
        return $this->service
                    ->create($payload);
    }

    public function update($id, $payload) {
        return $this->service
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->service->destroy($id);
    }
}