<?php

namespace App\Repositories;

use App\Models\BarberSchedule;

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
        return BarberSchedule::whereDate('selected_date_and_time', $data['selected_day'])->get();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getReserveByCustomer(int $id): mixed
    {
        return BarberSchedule::select('service_type_id', 'selected_date_and_time', 'observation', 'barbers_schedules.barber_id')
            ->join('services_registers', 'barbers_schedules.service_register_id', '=', 'services_registers.id')
            ->where('barbers_schedules.customer_id', $id)
            ->get();
    }
}