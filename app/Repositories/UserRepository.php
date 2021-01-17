<?php

namespace App\Repositories;

use App\User;

class UserRepository
{
    public function __construct(User $user) {
        $this->user = $user;
    }
    public function all() {
        return $this->user->all();
    }

    public function show($column, $value) {
        return $this->user
                    ->where($column, $value)
                    ->first();
    }

    public function create($payload) {
        return $this->user
                    ->create($payload);
    }

    public function update($id, $payload) {
        return $this->user
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->user->destroy($id);
    }
}