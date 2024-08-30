<?php

namespace Tests\Unit\Customer;

use App\Repositories\BarberScheduleRepository;
use App\Repositories\BarberWorkingHourRepository;
use App\Services\Customer\GetAvailableTimesOfBarberService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

// in construction
class GetAvailableTimesOfBarberTest extends TestCase
{
    use RefreshDatabase;
    
    protected $barberWorkingHourRepository;
    protected $barberScheduleRepository;
    protected $getAvailableTimesOfBarberService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->barberWorkingHourRepository = Mockery::mock(BarberWorkingHourRepository::class);
        $this->barberScheduleRepository = Mockery::mock(BarberScheduleRepository::class);
        $this->getAvailableTimesOfBarberService = new GetAvailableTimesOfBarberService($this->barberWorkingHourRepository, $this->barberScheduleRepository);
    }

    public function testGetTimesAvailable(): void
    {    
        $this->barberWorkingHourRepository
            ->shouldReceive('getBarberWithWorkingHours')
            ->with(1)
            ->andReturn((object) [
                'start_work' => '09:00',
                'end_work' => '19:00'
        ]);

        $this->barberScheduleRepository
            ->shouldReceive('getScheduleDayBarber')
            ->andReturn([]);

        $schduleAvailable = $this->getAvailableTimesOfBarberService->getTimes([
            'barber_id' => 1,
            'service_id' => 1,
            'selected_day' => '2024-08-08'
        ]);

        $this->assertIsArray($schduleAvailable);
    }
}