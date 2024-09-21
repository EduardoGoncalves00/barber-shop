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
}