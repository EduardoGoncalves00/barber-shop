<?php


namespace App\Services\Customer;

use App\Exceptions\BarberDoesNotExistException;
use App\Exceptions\SelectedDayInvalidException;
use App\Exceptions\ServiceTypeDoesNotExistException;
use App\Repositories\BarberScheduleRepository;
use App\Repositories\BarberWorkingHourRepository;
use App\Repositories\ServiceTypeRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;

abstract class GetAvailableTimesOfBarberAbstractService 
{
    protected $scheduleDay = [];
    protected $barberWorkingHourRepository;
    protected $barberScheduleRepository;

    public function __construct(BarberWorkingHourRepository $barberWorkingHourRepository, BarberScheduleRepository $barberScheduleRepository)
    {
        $this->barberWorkingHourRepository = $barberWorkingHourRepository;
        $this->barberScheduleRepository = $barberScheduleRepository;
    }

    /**
     * @param array $data
     * @return array
     */
    public function getTimes(array $data): array
    {
        $this->validations($data);

        $barber = $this->barberWorkingHourRepository->getBarberWithWorkingHours($data['barber_id']);

        $this->scheduleDay = $this->mountScheduleDay($barber->start_work, $barber->end_work);

        return $this->filterAvailableTimes($data);
    }
    
    /**
     * @param string $startWork
     * @param string $endWork
     * @return array
     */
    protected function mountScheduleDay(string $startWork, string $endWork): array
    {
        $startWork = Carbon::createFromTimeString($startWork);
        $endWork = Carbon::createFromTimeString($endWork);
    
        $times = [];
    
        $lunchStart = Carbon::createFromTimeString('12:00');
        $lunchEnd = Carbon::createFromTimeString('13:00');
        
        while ($startWork < $endWork) {
            if ($startWork < $lunchStart || $startWork >= $lunchEnd) {
                $times[] = $startWork->format('H:i');
            }
            $startWork->addMinutes(30);
        }
        
        return $times;
    }
    
    /**
     * @param array $data
     * @return array
     */
    protected function filterAvailableTimes(array $data): array
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
    protected function availableTimes(int $totalSlots, int $requiredSlot, array $hoursOccupied): array
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
    protected function getHoursMarkedDaySelected(array $data): array
    {
        $getHoursMarkedDaySelected = $this->barberScheduleRepository->getScheduleDayBarber($data);

        $hoursOccupied = [];
        
        foreach ($getHoursMarkedDaySelected as $value) {
            $hoursOccupied[] = Carbon::createFromTimeString($value->selected_date_and_time)->format('H:i');
        }

        return $hoursOccupied;
    }

    /**
     * @param array $data
     * @return void
     * 
     * @throws BarberDoesNotExistException
     * @throws ServiceTypeDoesNotExistException
     * @throws SelectedDayInvalidException
    */
    protected function validations(array $data): void
    {
        $barber = app(UserRepository::class)->find($data['barber_id']);
        if (!$barber) {
            throw new BarberDoesNotExistException();
        }

        $service = app(ServiceTypeRepository::class)->find($data['service_id']);
        if (!$service) {
            throw new ServiceTypeDoesNotExistException();
        }

        if (Carbon::parse($data['selected_day'])->isPast()) {
            throw new SelectedDayInvalidException();
        }
    }
}