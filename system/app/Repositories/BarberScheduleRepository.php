<?php

namespace App\Repositories;

use App\Models\BarbersSchedules;

class BarberScheduleRepository
{
    /**
     * @param array $data
     */
    public function getScheduleDayBarber(array $data)
    {
        return BarbersSchedules::where('barber_id', $data['barber_id'])
        ->whereDate('selected_date_and_time', $data['selected_day'])
        ->get();
    }
}