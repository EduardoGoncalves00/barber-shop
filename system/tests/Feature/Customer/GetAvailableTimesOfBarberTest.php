<?php

namespace Tests\Feature\Customer;

use App\Models\BarbersWorkingHours;
use App\Models\ServicesTypes;
use App\Models\User;
use App\Repositories\BarberScheduleRepository;
use App\Repositories\BarbersWorkingHoursRepository;
use App\Services\Customer\GetAvailableTimesOfBarberService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// in construction
class GetAvailableTimesOfBarberTest extends TestCase
{
    use RefreshDatabase;
    
    protected $barber;
    protected $barberWorkingHours;
    protected $serviceType;
    protected $getAvailableTimesOfBarberService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->barber = User::factory()->create([
            'type' => 'barber'
        ]);

        $this->barberWorkingHours = BarbersWorkingHours::factory()->create([
            'barber_id' => $this->barber->id
        ]);

        $this->serviceType = ServicesTypes::factory()->create([
            'service_name' => 'hair',
            'value' => 50,
            'estimated_time' => '00:30:00'
        ]);

        $barbersWorkingHoursRepository = new BarbersWorkingHoursRepository();
        $barberScheduleRepository = new BarberScheduleRepository();
        $this->getAvailableTimesOfBarberService = new GetAvailableTimesOfBarberService($barbersWorkingHoursRepository, $barberScheduleRepository);
    }

    public function testGetTimesAvailable(): void
    {    
        $schduleAvailable = $this->getAvailableTimesOfBarberService->getTimes([
            'barber_id' => $this->barber->id,
            'service_id' => $this->serviceType->id,
            'selected_day' => '2024-08-08'
        ]);

        $this->assertIsArray($schduleAvailable);
    }
}