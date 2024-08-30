<?php

namespace Tests\EndToEnd\Barber;

use App\Repositories\UserRepository;
use App\Services\Barber\BarberService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class BarberUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $barber;
    protected $autentication;
    protected $barberService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->barber = $this->postJson('/api/create-barber', [
            'name' => 'William Boeiraa',
            'email' => 'william.boeira@gmail.com',
            'phone' => '4106805284',
            'password' => 'william123',
            'start_lunch' => '12:00',
            'end_lunch' => '13:00',
            'start_work' => '09:00',
            'end_work' => '19:00'
        ]);

        $this->autentication = $this->postJson('/api/login', [
            'email' => 'william.boeira@gmail.com',
            'password' => 'william123'
        ]);
    }

    public function testSuccess(): void
    {
        $response = $this->post('/api/barber/update', [
            'name' => 'William Silva',
            'phone' => '9006005211',
            'password' => 'silva123',
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '08:00',
            'end_work' => '18:00'
        ], [
            'Authorization' => 'Bearer ' . $this->autentication['data']['token']
        ]);

        $response->assertStatus(200);
        $this->assertEquals("Success when updating.", $response['message']);
    }

    public function testError(): void
    {
        $this->barberService = Mockery::mock(BarberService::class);
        $this->app->instance(BarberService::class, $this->barberService);

        $this->barberService
            ->shouldReceive('update')
            ->once()
            ->with([
                'name' => 'William Silva',
                'phone' => '9006005211',
                'password' => 'silva123',
                'start_lunch' => '13:00',
                'end_lunch' => '14:00',
                'start_work' => '08:00',
                'end_work' => '18:00'
            ])
            ->andThrow(new \Exception('General error'));

        $response = $this->postJson('/api/barber/update', [
            'name' => 'William Silva',
            'phone' => '9006005211',
            'password' => 'silva123',
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '08:00',
            'end_work' => '18:00'
        ]);

        $response->assertStatus(400);       
        $this->assertEquals("Error when updating.", $response['message']); 
    }

    public function testUpdateFieldSpecific(): void
    {
        $response = $this->post('/api/barber/update', [
            'name' => 'William Silva Atualizado'
        ], [
            'Authorization' => 'Bearer ' . $this->autentication['data']['token']
        ]);

        $response->assertStatus(200);
        $this->assertEquals("Success when updating.", $response['message']);
    }

    public function testDoNotUpdateUserType(): void
    {
        $this->postJson('/api/barber/update', [
            'type' => 'customer',
            'name' => 'William Silva',
            'phone' => '9006005211',
            'password' => 'silva123',
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '08:00',
            'end_work' => '18:00'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'william.boeira@gmail.com',
            'type' => 'barber'
        ]);
    }

    public function testUpdatedInTableUsers(): void
    {
        $this->postJson('/api/barber/update', [
            'name' => 'William Silva',
            'phone' => '9006005211',
            'password' => 'silva123',
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '08:00',
            'end_work' => '18:00'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'William Silva',
            'phone' => '9006005211',
            'password' => app(UserRepository::class)->find(1)->password,
            'type' => 'barber'
        ]);
    }

    public function testUpatedInTableBarbersWorkingHours(): void
    {
        $this->postJson('/api/barber/update', [
            'name' => 'William Silva',
            'phone' => '9006005211',
            'password' => 'silva123',
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '08:00',
            'end_work' => '18:00'
        ]);

        $this->assertDatabaseHas('barbers_working_hours', [
            'barber_id' => 1,
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '08:00',
            'end_work' => '18:00'
        ]);
    }

    public function testPasswordSuccess(): void
    {
        $response = $this->post('/api/barber/update', [
            'name' => 'William Silva',
            'phone' => '9006005211',
            'password' => 'silva123',
            'start_lunch' => '13:00',
            'end_lunch' => '14:00',
            'start_work' => '08:00',
            'end_work' => '18:00'
        ], [
            'Authorization' => 'Bearer ' . $this->autentication['data']['token']
        ]);

        $response->assertStatus(200);
        $this->assertEquals("Success when updating.", $response['message']);
    }
}
