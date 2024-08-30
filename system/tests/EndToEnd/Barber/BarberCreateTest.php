<?php

namespace Tests\EndToEnd\Barber;

use App\Services\Barber\BarberService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class BarberCreateTest extends TestCase
{
    use RefreshDatabase;

    protected $barberService;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testSuccess(): void
    {
        $response = $this->postJson('/api/create-barber', [
            'name' => 'Williaaam Boeiraa',
            'email' => 'williaam.boeira@gmail.com',
            'phone' => '4106805284',
            'password' => 'willaidam123',
            'start_lunch' => '10:00',
            'end_lunch' => '11:00',
            'start_work' => '08:00',
            'end_work' => '19:00',
        ]);

        $response->assertStatus(200);
        $this->assertEquals("Success when creating.", $response['message']);
    }

    public function testError(): void
    {
        $this->barberService = Mockery::mock(BarberService::class);
        $this->app->instance(BarberService::class, $this->barberService);

        $this->barberService
            ->shouldReceive('create')
            ->once()
            ->with([
                'name' => 'Williaaam Boeiraa',
                'email' => 'williaam.boeira@gmail.com',
                'phone' => '4106805284',
                'password' => 'willaidam123',
                'start_work' => '08:00',
                'end_work' => '19:00',
                'start_lunch' => '10:00',
                'end_lunch' => '11:00'
            ])
            ->andThrow(new \Exception('General error'));

        $response = $this->postJson('/api/create-barber', [
            'name' => 'Williaaam Boeiraa',
            'email' => 'williaam.boeira@gmail.com',
            'phone' => '4106805284',
            'password' => 'willaidam123',
            'start_lunch' => '10:00',
            'end_lunch' => '11:00',
            'start_work' => '08:00',
            'end_work' => '19:00'
        ]);

        $response->assertStatus(400);       
        $this->assertEquals("Error when creating.", $response['message']); 
    }

    public function testEmailAlreadyRegistered(): void
    {
        $this->postJson('/api/create-barber', [
            'name' => 'Williaaam Boeiraa',
            'email' => 'williaam.boeira@gmail.com',
            'phone' => '4106805284',
            'password' => 'willaidam123',
            'start_lunch' => '10:00',
            'end_lunch' => '11:00',
            'start_work' => '08:00',
            'end_work' => '19:00'
        ]);

        $response = $this->postJson('/api/create-barber', [
            'name' => 'Another Name',
            'email' => 'williaam.boeira@gmail.com',
            'phone' => '1234567890',
            'password' => 'anotherpassword',
            'start_lunch' => '11:00',
            'end_lunch' => '12:00',
            'start_work' => '09:00',
            'end_work' => '18:00'
        ]);

        $response->assertStatus(400);
        $this->assertEquals("E-mail already registered.", $response['message']); 
    }

    public function testRequiredField(): void
    {
        $response = $this->postJson('/api/create-barber', [
            'email' => 'williaam.boeira@gmail.com',
            'phone' => '1234567890',
            'password' => 'anotherpassword',
            'start_lunch' => '11:00',
            'end_lunch' => '12:00',
            'start_work' => '09:00',
            'end_work' => '18:00'
        ]);
        
        $response->assertStatus(422); 
        $this->assertEquals("The name field is required.", $response['message']);
    }

    public function testCreatedTypeBarber(): void
    {
        $response = $this->postJson('/api/create-barber', [
            'name' => 'Williaaam Boeiraa',
            'email' => 'williaam.boeira@gmail.com',
            'phone' => '4106805284',
            'password' => 'willaidam123',
            'start_lunch' => '10:00',
            'end_lunch' => '11:00',
            'start_work' => '08:00',
            'end_work' => '19:00'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'williaam.boeira@gmail.com',
            'type' => 'barber'
        ]);
    }

    public function testCreatedInTableUsers(): void
    {
        $response = $this->postJson('/api/create-barber', [
            'name' => 'Williaaam Boeiraa',
            'email' => 'williaam.boeira@gmail.com',
            'phone' => '4106805284',
            'password' => 'willaidam123',
            'start_lunch' => '10:00',
            'end_lunch' => '11:00',
            'start_work' => '08:00',
            'end_work' => '19:00'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Williaaam Boeiraa',
            'email' => 'williaam.boeira@gmail.com',
            'phone' => '4106805284'
        ]);
    }

    public function testCreatedInTableBarbersWorkingHours(): void
    {
        $response = $this->postJson('/api/create-barber', [
            'name' => 'Williaaam Boeiraa',
            'email' => 'williaam.boeira@gmail.com',
            'phone' => '4106805284',
            'password' => 'willaidam123',
            'start_lunch' => '10:00',
            'end_lunch' => '11:00',
            'start_work' => '08:00',
            'end_work' => '19:00'
        ]);

        $this->assertDatabaseHas('barbers_working_hours', [
            'barber_id' => 1,
            'start_lunch' => '10:00',
            'end_lunch' => '11:00',
            'start_work' => '08:00',
            'end_work' => '19:00'
        ]);
    }
}
