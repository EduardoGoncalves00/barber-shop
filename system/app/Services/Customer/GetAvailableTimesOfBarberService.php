<?php

namespace App\Services\Customer;

use App\Repositories\BarberScheduleRepository;
use App\Repositories\BarbersWorkingHoursRepository;
use Carbon\Carbon;

class GetAvailableTimesOfBarberService 
{
    protected $scheduleDay = [];
    protected $barbersWorkingHoursRepository;
    protected $barberScheduleRepository;

    public function __construct(BarbersWorkingHoursRepository $barbersWorkingHoursRepository, BarberScheduleRepository $barberScheduleRepository)
    {
        $this->barbersWorkingHoursRepository = $barbersWorkingHoursRepository;
        $this->barberScheduleRepository = $barberScheduleRepository;
    }

    /**
     * @param array $data
     * @return array
     */
    public function getTimes(array $data): array
    {
        $barber = $this->barbersWorkingHoursRepository->getBarberWithWorkingHours($data['barber_id']);

        $this->scheduleDay = $this->mountScheduleDay($barber->start_work, $barber->end_work);

        return $this->filterAvailableTimes($data);
    }
    
    /**
     * @param string $startWork
     * @param string $endWork
     * @return array
     */
    private function mountScheduleDay($startWork, $endWork): array
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
        $durationInMinutesService = 30;

        $hoursOccupied = $this->getHoursMarkedDaySelected($data);

        $requiredSlot = $durationInMinutesService / 30;

        $totalSlots = count($this->scheduleDay);

        return $this->availableTimes($totalSlots, $requiredSlot, $hoursOccupied);
    }

    /**
     * @param int $totalSlots
     * @param int $requiredSlot
     * @param array $hoursOccupied
     * @return array
     */
    private function availableTimes(int $totalSlots, int $requiredSlot, array $hoursOccupied): array
    {
        for ($i = 0; $i < $totalSlots; $i++) {
            
            $getSlotsSchedule = array_slice($this->scheduleDay, $i, $requiredSlot);
            
            $slotsAvailable = array_intersect($getSlotsSchedule, $hoursOccupied);

            if (empty($slotsAvailable)) {
                $availableTimes[] = $this->scheduleDay[$i];
            }
        }

        return $availableTimes;
    }

    /**
     * @param array $data
     * @return array
     */
    private function getHoursMarkedDaySelected(array $data): array
    {
        $getHoursMarkedDaySelected = $this->barberScheduleRepository->getScheduleDayBarber($data);

        $hoursOccupied = [];
        
        foreach ($getHoursMarkedDaySelected as $value) {
            $hoursOccupied[] = Carbon::createFromTimeString($value->selected_date_and_time)->format('H:i');
        }

        return $hoursOccupied;
    }
}