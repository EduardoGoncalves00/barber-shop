<?php

namespace Tests\Unit\Barber;

use App\Repositories\BarberWorkingHourRepository;
use App\Repositories\UserRepository;
use Tests\TestCase;
use App\Services\Barber\BarberService;
use Illuminate\Support\Facades\Auth;
use Mockery;

class BarberUpdateTest extends TestCase
{
    protected $userRepository;
    protected $barberWorkingHourRepository;
    protected $barberService;
    protected $barber;

    protected function setUp(): void
    {
        parent::setUp();

        $this->barber = (object) [
            'id' => 1,
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'type' => 'barber'
        ];

        Auth::shouldReceive('user')->andReturn($this->barber);

        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->barberWorkingHourRepository = Mockery::mock(BarberWorkingHourRepository::class);
        $this->barberService = new BarberService($this->userRepository, $this->barberWorkingHourRepository);
    }

    public function testUpdateBarber(): void
    {
        $this->userRepository
            ->shouldReceive('update')
            ->andReturn();
        
        $this->barberWorkingHourRepository
            ->shouldReceive('update')
            ->andReturn();

        $this->userRepository
            ->shouldReceive('getByEmail')
            ->with('eduardo@example.com')
            ->andReturn(false);

        $customerUpdated = $this->barberService->update([
            'name' => 'Eduardo Boeira',
            'phone' => '(51) 8888-8888',
            'password' => 'password',
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '09:00',
            'end_work' => '20:00'
        ]);

        $this->assertTrue($customerUpdated);
    }

    public function testUpdatePasswordBarber(): void
    {
        $this->userRepository
            ->shouldReceive('update')
            ->andReturn();
        
        $this->barberWorkingHourRepository
            ->shouldReceive('update')
            ->andReturn();

        $this->userRepository
            ->shouldReceive('getByEmail')
            ->with('eduardo@example.com')
            ->andReturn(false);

        $customerUpdated = $this->barberService->update([
            'password' => 'password',
        ]);

        $this->assertTrue($customerUpdated);
    }
};