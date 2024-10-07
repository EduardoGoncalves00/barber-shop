<?php

namespace App\Repositories;

use App\Models\BarberWorkingHour;
use App\Models\User;

class BarberWorkingHourRepository
{
    /**
     * @param int $barberID
     * @return mixed
     */
    public function getBarberWithWorkingHours(int $barberID): mixed
    {
        return BarberWorkingHour::where('barber_id', $barberID)
            ->select('start_work', 'end_work', 'start_lunch', 'end_lunch')
            ->first();
    }

    /**
     * @param array $data
     * @return BarberWorkingHour
     */
    public function create(array $data): BarberWorkingHour
    {
        return BarberWorkingHour::create($data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $barber = BarberWorkingHour::where('barber_id', $data['id'])->firstOrFail();
        return $barber->update($data);
    }
}