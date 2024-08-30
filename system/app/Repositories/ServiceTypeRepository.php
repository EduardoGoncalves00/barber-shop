<?php

namespace App\Repositories;

use App\Models\ServiceType;

class ServiceTypeRepository
{
    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return ServiceType::find($id);
    }
}