<?php

namespace App\Repositories;

use App\Models\ServiceType;

class ServiceTypeRepository
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return ServiceType::all();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return ServiceType::find($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        return ServiceType::create($data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function update(array $data): mixed
    {
        $service = ServiceType::findOrFail($data['id']);
        return $service->update($data);    
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return ServiceType::destroy($id);
    }
}