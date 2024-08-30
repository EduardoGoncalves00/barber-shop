<?php

namespace Tests\EndToEnd\Customer;

use App\Models\ServicesTypes;
use App\Services\Customer\GetAvailableTimesOfBarberService;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class GetAvailableTimesOfBarberTest extends TestCase
{
    use RefreshDatabase;

    protected $serviceType;
    protected $getAvailableTimesOfBarberService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->postJson('/api/create-barber', [
            'name' => 'William Boeiraa',
            'email' => 'william.boeira@gmail.com',
            'phone' => '4106805284',
            'password' => 'william123',
            'start_lunch' => '12:00',
            'end_lunch' => '13:00',
            'start_work' => '09:00',
            'end_work' => '19:00'
        ]);

        $this->postJson('/api/create-customer', [
            'name' => 'Thiago Silva',
            'phone' => '5907007080',
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiago123'
        ]);

        $this->postJson('/api/login', [
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiago123'
        ]);

        // in contruction
        $this->serviceType = ServicesTypes::factory()->create([
            'service_name' => 'hair',
            'value' => 50,
            'estimated_time' => '00:30:00'
        ]);
    }

    public function testSuccess(): void
    {
        $response = $this->postJson('/api/customer/get-available-times-of-barber', [
            'barber_id' => 1,
            'service_id' => 1,
            'selected_day' => Carbon::tomorrow()->format('Y-m-d')
        ]);

        $response->assertStatus(200);
        $this->assertEquals("Success when get available times of barber.", $response['message']);
    }

    public function testError(): void
    {
        $getAvailableTimesOfBarberService = Mockery::mock(GetAvailableTimesOfBarberService::class);

        $getAvailableTimesOfBarberService
            ->shouldReceive('getTimes')
            ->once()
            ->with([
                'barber_id' => 1,
                'service_id' => 1,
                'selected_day' => Carbon::tomorrow()->format('Y-m-d')
            ])
            ->andThrow(new \Exception('General error'));

        $this->app->instance(GetAvailableTimesOfBarberService::class, $getAvailableTimesOfBarberService);

        $response = $this->postJson('/api/customer/get-available-times-of-barber', [
            'barber_id' => 1,
            'service_id' => 1,
            'selected_day' => Carbon::tomorrow()->format('Y-m-d')
        ]);

        $response->assertStatus(400);
        $this->assertEquals("Error when get available times of barber.", $response['message']);
    }

    public function testRequiredField(): void
    {
        $response = $this->postJson('/api/customer/get-available-times-of-barber', [
            'barber_id' => 1,
            'selected_day' => Carbon::tomorrow()->format('Y-m-d')
        ]);

        $response->assertStatus(422); 
        $this->assertEquals("The service id field is required.", $response['message']);
    }
}