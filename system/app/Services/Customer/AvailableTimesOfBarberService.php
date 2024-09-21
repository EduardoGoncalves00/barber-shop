<?php

namespace App\Services\Customer;

class AvailableTimesOfBarberService extends GetAvailableTimesOfBarberAbstractService
{
    /**
     * @param array $data
     * @return array
    */
    public function getAvailableTimes(array $data): array
    {
        return $this->getTimes($data);
    }
}