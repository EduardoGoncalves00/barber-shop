<?php

namespace Tests\Unit\Customer;

use App\Repositories\BarberScheduleRepository;
use App\Repositories\BarbersWorkingHoursRepository;
use App\Services\Customer\GetAvailableTimesOfBarberService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

// in construction
class GetScheduleAvailableBarberTest extends TestCase
{
    use RefreshDatabase;
    
    protected $barbersWorkingHoursRepository;
    protected $barberScheduleRepository;
    protected $getAvailableTimesOfBarberService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->barbersWorkingHoursRepository = Mockery::mock(BarbersWorkingHoursRepository::class);
        $this->barberScheduleRepository = Mockery::mock(BarberScheduleRepository::class);
        $this->getAvailableTimesOfBarberService = new GetAvailableTimesOfBarberService($this->barbersWorkingHoursRepository, $this->barberScheduleRepository);
    }

    public function testGetScheduleAvailable(): void
    {    
        $this->barbersWorkingHoursRepository
            ->shouldReceive('getBarberWithWorkingHours')
            ->with(1)
            ->andReturn((object) [
                'start_work' => '09:00',
                'end_work' => '19:00'
        ]);

        $this->barberScheduleRepository
            ->shouldReceive('getScheduleDayBarber')
            ->andReturn([]);

        $schduleAvailable = $this->getAvailableTimesOfBarberService->getScheduleAvailableBarber([
            'barber_id' => 1,
            'service_id' => 1,
            'selected_day' => '2024-08-08'
        ]);

        $this->assertIsArray($schduleAvailable);
    }
}