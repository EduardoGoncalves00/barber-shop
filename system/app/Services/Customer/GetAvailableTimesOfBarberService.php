<?php

namespace App\Services\Customer;

use App\Repositories\BarberScheduleRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;

class GetAvailableTimesOfBarberService 
{
    protected $scheduleDayMounted = [];

    /**
     * @param array $data
     * @return array
     */
    public function getScheduleAvailableBarber(array $data): array
    {
        $barber = app(UserRepository::class)->find($data['barber_id']);
    
        $this->scheduleDayMounted = $this->mountScheduleStructureOfDay($barber['start_work'], $barber['end_work']);
        
        return $this->filterAvailableTimes($data);
    }
    
    /**
     * @param string $startWork
     * @param string $endWork
     * @return array
     */
    private function mountScheduleStructureOfDay($startWork, $endWork): array
    {
        $startWork = Carbon::createFromTimeString($startWork);
        $endWork = Carbon::createFromTimeString($endWork);
    
        $times = [];
    
        while ($startWork < $endWork) {
            $times[] = $startWork->format('H:i');
            $startWork->addMinutes(30);
        }
    
        return $times;
    }
    
    /**
     * @param array $data
     * @return array
     */
    private function filterAvailableTimes(array $data): array
    {
        $getTimesMarkedOfDay = app(BarberScheduleRepository::class)->getScheduleDayBarber($data);

        $occupiedSlots = [];
        foreach ($getTimesMarkedOfDay as $value) {
            $occupiedSlots[] = Carbon::createFromTimeString($value->selected_date_and_time)->format('H:i');
        }

        $availableTimes = [];
        $durationInMinutes = 30;
        $requiredSlots = $durationInMinutes / 30;
        $totalSlots = count($this->scheduleDayMounted);

        for ($i = 0; $i < $totalSlots; $i++) {
            
            $getSlotsSchedule = array_slice($this->scheduleDayMounted, $i, $requiredSlots);

            $slotsAvailable = array_intersect($getSlotsSchedule, $occupiedSlots);        
            $isAvailable = empty($slotsAvailable);

            if ($isAvailable) {
                $availableTimes[] = $this->scheduleDayMounted[$i];
            }
        }

        return $availableTimes;
    }
}