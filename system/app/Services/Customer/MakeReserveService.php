<?php

namespace App\Services\Customer;

use App\Exceptions\HoursNotAvailableException;
use App\Repositories\BarberScheduleRepository;
use App\Repositories\ServiceRegisterRepository;
use Carbon\Carbon;

class MakeReserveService extends GetAvailableTimesOfBarberAbstractService 
{
    /**
     * @param array $data
     * @return mixed
     * @throws HoursNotAvailableException
    */
    public function makeReserve(array $data): mixed
    {
        $data['selected_day'] = Carbon::parse($data['selected_day_and_time'])->format('Y-m-d');
        $selectedMinute = Carbon::parse($data['selected_day_and_time'])->format('H:i');

        $availableTimes = $this->getTimes($data);

        if (!in_array($selectedMinute, $availableTimes)) {
            throw new HoursNotAvailableException();
        }

        $serviceRegisterID = $this->createServiceRegister($data);
        return $this->createBarberSchedule($data, $serviceRegisterID);
    }

    /**
     * @param array $data
     * @return int
    */    
    private function createServiceRegister(array $data): int
    {
        $data['customer_id'] = auth()->user()->id;
        $data['service_type_id'] = $data['service_id'];

        $serviceRegister = new ServiceRegisterRepository();
        $serviceRegisterID = $serviceRegister->create($data);
        return $serviceRegisterID->id;
    }

    /**
     * @param array $data
     * @param int $serviceRegisterID
     * @return mixed
    */
    private function createBarberSchedule(array $data, int $serviceRegisterID): mixed
    {
        $data['customer_id'] = auth()->user()->id;
        $data['service_register_id'] = $serviceRegisterID;

        $barberSchedule = new BarberScheduleRepository();
        return $barberSchedule->create($data);
    }
}