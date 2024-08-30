<?php

namespace Tests\Feature\Barber;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Repositories\BarbersWorkingHoursRepository;
use App\Repositories\UserRepository;
use App\Services\Barber\BarberService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BarberCreateTest extends TestCase
{
    use RefreshDatabase;
    
    protected $barberWorkingHours;
    protected $barberService;
    protected $barber;

    protected function setUp(): void
    {
        parent::setUp();

        $userRepository = new UserRepository();
        $barbersWorkingHoursRepository = new BarbersWorkingHoursRepository();
        $this->barberService = new BarberService($userRepository, $barbersWorkingHoursRepository);
    }

    public function testCreateBarber(): void
    {    
        $barberCreated = $this->barberService->create([
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
            'password' => 'password',
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '09:00',
            'end_work' => '20:00'
        ]);

        $this->assertTrue($barberCreated);
    }

    public function testCreateBarberExpectedTypeBarber(): void
    {    
        $this->barberService->create([
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
            'password' => 'password',
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '09:00',
            'end_work' => '20:00'
        ]);

        $barberType = app(UserRepository::class)->getByEmail('eduardo@example.com');
        
        $this->assertEquals('barber', $barberType->type);
    }

    public function testCreateBarberEmailRegistered(): void
    {
        $this->expectException(EmailAlreadyRegisteredException::class);

        $this->barberService->create([
            'name' =>' Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
            'password' => 'password',
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '09:00',
            'end_work' => '20:00'
        ]);

        $this->barberService->create([
            'email' => 'eduardo@example.com',
        ]);
    }
}