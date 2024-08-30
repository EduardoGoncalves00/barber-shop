<?php

namespace Tests\Feature\Barber;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Models\BarbersWorkingHours;
use App\Models\User;
use App\Repositories\BarbersWorkingHoursRepository;
use App\Repositories\UserRepository;
use App\Services\Barber\BarberService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BarberUpdateTest extends TestCase
{
    use RefreshDatabase;
    
    protected $barber;
    protected $barberWorkingHours;
    protected $barberService;

    protected function setUp(): void
    {
        parent::setUp();

        $userRepository = new UserRepository();
        $barbersWorkingHoursRepository = new BarbersWorkingHoursRepository();
        $this->barberService = new BarberService($userRepository, $barbersWorkingHoursRepository);

        $this->barber = User::factory()->create([
            'type' => 'barber'
        ]);

        $this->barberWorkingHours = BarbersWorkingHours::factory()->create([
            'barber_id' => $this->barber->id
        ]);
    }

    public function testUpdateBarber(): void
    {
        Sanctum::actingAs($this->barber);
    
        $barberUpdated = $this->barberService->update([
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
            'password' => 'password',
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '09:00',
            'end_work' => '20:00'
        ]);

        $this->assertTrue($barberUpdated);
    }

    public function testExpectsToUpatedInUserTable(): void
    {
        Sanctum::actingAs($this->barber);
    
        $barberUpdated = $this->barberService->update([
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
            'password' => 'password'
        ]);

        $this->assertTrue($barberUpdated);

        $this->assertDatabaseHas('users', [
            'id' => $this->barber->id,
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
        ]);
    }

    public function testExpectsToUpatedInWorkinHoursTable(): void
    {
        Sanctum::actingAs($this->barber);

        $barberUpdated = $this->barberService->update([
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '09:00',
            'end_work' => '20:00'
        ]);

        $this->assertTrue($barberUpdated);
        
        $this->assertDatabaseHas('barbers_working_hours', [
            'barber_id' => $this->barber->id,
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '09:00',
            'end_work' => '20:00'
        ]);
    }
}