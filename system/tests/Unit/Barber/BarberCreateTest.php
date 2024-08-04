<?php

namespace Tests\Unit\Barber;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Models\User;
use App\Repositories\BarbersWorkingHoursRepository;
use App\Repositories\UserRepository;
use Tests\TestCase;
use App\Services\Barber\BarberService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class BarberCreateTest extends TestCase
{
    protected $barberService;
    protected $userRepository;
    protected $barbersWorkingHoursRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->barbersWorkingHoursRepository = Mockery::mock(BarbersWorkingHoursRepository::class);
        $this->barberService = new BarberService($this->userRepository, $this->barbersWorkingHoursRepository);
    }

    public function testCreateBarber(): void
    {
        $barber = New User;
        $barber->id = 1;

        $this->userRepository
            ->shouldReceive('create')
            ->andReturn($barber);
        
        $this->barbersWorkingHoursRepository
            ->shouldReceive('create')
            ->andReturn();

        $this->userRepository
            ->shouldReceive('getByEmail')
            ->with('william@example.com')
            ->andReturn(false);

        $barber = $this->barberService->create([
            'name' =>' William Boeira',
            'email' => 'william@example.com',
            'phone' => '(51) 9999-9999',
            'password' => 'password',
            'start_lunch' => '12:00',
            'end_lunch' => '13:00',
            'start_work' => '08:00',
            'end_work' => '19:00'
        ]);

        $this->assertTrue($barber);
    }

    public function testCreateBarberExpectedTypeBarber(): void
    {
        $barber = New User;
        $barber->id = 1;

        $this->userRepository
            ->shouldReceive('create')
            ->andReturn($barber);
        
        $this->barbersWorkingHoursRepository
            ->shouldReceive('create')
            ->andReturn();

        $this->userRepository
            ->shouldReceive('getByEmail')
            ->with('william@example.com')
            ->andReturn(false);

        $barber = $this->barberService->create([
            'name' =>' William Boeira',
            'email' => 'william@example.com',
            'phone' => '(51) 9999-9999',
            'password' => 'password',
            'start_lunch' => '12:00',
            'end_lunch' => '13:00',
            'start_work' => '08:00',
            'end_work' => '19:00'
        ]);

        $this->userRepository
            ->shouldHaveReceived('create')
            ->with(Mockery::on(function ($data) {
                return $data['type'] === 'barber';
        }));
    }

    public function testCreateEmailRegistered(): void
    {
        $this->expectException(EmailAlreadyRegisteredException::class);

        $this->userRepository
            ->shouldReceive('getByEmail')
            ->with('william@example.com')
            ->andReturn(true);

        $this->barberService->create([
            'name' =>' William Boeira',
            'email' => 'william@example.com',
            'phone' => '(51) 9999-9999',
            'password' => 'password',
            'start_lunch' => '12:00',
            'end_lunch' => '13:00',
            'start_work' => '08:00',
            'end_work' => '19:00'
        ]);
    }
}
