<?php

namespace Tests\EndToEnd\Customer;

use App\Repositories\UserRepository;
use App\Services\Customer\CustomerService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class CustomerUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $autentication;
    protected $customerService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = $this->postJson('/api/create-customer', [
            'name' => 'Thiago Silva',
            'phone' => '5907007080',
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiago123'
        ]);

        $this->autentication = $this->postJson('/api/login', [
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiago123'
        ]);
    }

    public function testSuccess(): void
    {
        $response = $this->post('/api/customer/update', [
            'name' => 'Bruno Trindade',
            'phone' => '9908008010',
            'password' => 'bruno123'
        ], [
            'Authorization' => 'Bearer ' . $this->autentication['data']['token']
        ]);

        $response->assertStatus(200);
        $this->assertEquals("Success when updating.", $response['message']);
    }

    public function testError(): void
    {
        $this->customerService = Mockery::mock(CustomerService::class);
        $this->app->instance(CustomerService::class, $this->customerService);

        $this->customerService
            ->shouldReceive('update')
            ->once()
            ->with([
                'name' => 'Bruno Trindade',
                'phone' => '9908008010',
                'password' => 'bruno123'
            ])
            ->andThrow(new \Exception('General error'));

        $response = $this->postJson('/api/customer/update', [
            'name' => 'Bruno Trindade',
            'phone' => '9908008010',
            'password' => 'bruno123'
        ]);

        $response->assertStatus(400);       
        $this->assertEquals("Error when updating.", $response['message']); 
    }

    public function testUpdateFieldSpecific(): void
    {
        $response = $this->post('/api/customer/update', [
            'name' => 'Bruno Atualizado'
        ], [
            'Authorization' => 'Bearer ' . $this->autentication['data']['token']
        ]);

        $response->assertStatus(200);
        $this->assertEquals("Success when updating.", $response['message']);
    }

    public function testDoNotUpdateUserType(): void
    {
        $this->postJson('/api/customer/update', [
            'type' => 'barber',
            'name' => 'Bruno Trindade',
            'phone' => '9908008010',
            'password' => 'bruno123'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'thiago.silva@gmail.com',
            'type' => 'customer'
        ]);
    }

    public function testUpdatedInTableUsers(): void
    {
        $this->postJson('/api/customer/update', [
            'type' => 'barber',
            'name' => 'Bruno Trindade',
            'phone' => '9908008010',
            'password' => 'bruno123'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Bruno Trindade',
            'phone' => '9908008010',
            'password' => app(UserRepository::class)->find(1)->password,
            'type' => 'customer'
        ]);
    }
}
