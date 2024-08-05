<?php

namespace App\Repositories;

use App\Models\BarbersWorkingHours;

class BarbersWorkingHoursRepository
{
    /**
     * @param int $barberID
     * @return mixed
     */
    public function getBarberWithWorkingHours(int $barberID): mixed
    {
        return BarbersWorkingHours::join('users', 'barbers_working_hours.barber_id', '=', 'users.id')
        ->where('barbers_working_hours.barber_id', $barberID)
        ->select('barbers_working_hours.start_work', 'barbers_working_hours.end_work')
        ->first();
    }

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