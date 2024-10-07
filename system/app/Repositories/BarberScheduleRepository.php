<?php

namespace App\Repositories;

use App\Models\BarberSchedule;
use Illuminate\Support\Collection;

class BarberScheduleRepository
{
    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {   
        return BarberSchedule::create($data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getScheduleDayBarber(array $data): mixed
    {   
        return BarberSchedule::whereDate('selected_day_and_time', $data['selected_day'])->get();
    }

    /**
     * @param int $customerId
     * @return Collection
     */
    public function getReserveByCustomer(int $customerId): Collection
    {
        return BarberSchedule::with('serviceRegister')
            ->where('customer_id', $customerId)
            ->select('service_register_id', 'selected_day_and_time', 'observation', 'barber_id')
            ->get();
    }
}