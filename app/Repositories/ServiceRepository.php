<?php

namespace App\Repositories;

use App\Models\Service;
use App\Models\User;

class ServiceRepository extends Repository
{
    public function __construct(Service $user)
    {
        $this->model = $user;
    }

    public function serviceCountCurrentBusinessCenter(User $user) {

       return $this->model->whereHasCurrentBusinessCenter($user)->count();
    }

    public function serviceCurrentBusinessCenter(User $user) {

       return $this->model->whereHasCurrentBusinessCenter($user)->get();
    }
}
