<?php

namespace App\Services\Customer;

class MakeReserveService 
{
    // protected $scheduleDay = [];
    // protected $barberWorkingHourRepository;
    // protected $barberScheduleRepository;

    // public function __construct(BarberWorkingHourRepository $barberWorkingHourRepository, BarberScheduleRepository $barberScheduleRepository)
    // {
    //     $this->barberWorkingHourRepository = $barberWorkingHourRepository;
    //     $this->barberScheduleRepository = $barberScheduleRepository;
    // }

    /**
     * @param array $data
     * @return array // conferir
     */
    public function make(array $data)
    {
        dd($data);
        $data['customer_id'] = auth()->user()->id;
    }
}