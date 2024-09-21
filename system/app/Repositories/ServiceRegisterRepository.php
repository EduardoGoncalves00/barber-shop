<?php

namespace App\Repositories;

use App\Models\ServiceRegister;

class ServiceRegisterRepository
{
    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        return ServiceRegister::create($data);
    }
}