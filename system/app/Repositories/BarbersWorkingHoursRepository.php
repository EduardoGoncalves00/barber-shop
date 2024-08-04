<?php

namespace App\Repositories;

use App\Models\BarbersWorkingHours;

class BarbersWorkingHoursRepository
{
    /**
     * @param array $data
     * @return BarbersWorkingHours
     */
    public function create(array $data): BarbersWorkingHours
    {
        return BarbersWorkingHours::create($data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $barber = BarbersWorkingHours::where('barber_id', $data['id'])->firstOrFail();
        return $barber->update($data);
    }
}