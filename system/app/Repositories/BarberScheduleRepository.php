<?php

namespace App\Repositories;

use App\Models\BarberSchedule;

class BarberScheduleRepository
{
    /**
     * @param array $data
     */
    public function getScheduleDayBarber(array $data)
    {   
        return BarberSchedule::select('barbers_schedules.selected_date_and_time')
        ->join('services_registers', 'barbers_schedules.id', '=', 'services_registers.id')
        ->where('barbers_schedules.id', $data['barber_id'])
        ->whereDate('barbers_schedules.selected_date_and_time', $data['selected_day'])
        ->get();
    }
}