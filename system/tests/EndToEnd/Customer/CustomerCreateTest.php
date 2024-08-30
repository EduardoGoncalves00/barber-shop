<?php

namespace Tests\EndToEnd\Customer;

use App\Services\Customer\CustomerService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class CustomerCreateTest extends TestCase
{
    use RefreshDatabase;

    protected $customerService;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testSuccess(): void
    {
        $response = $this->postJson('/api/create-customer', [
            'name' => 'Thiago Silva',
            'phone' => '5907007080',
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiago123'
        ]);

        $response->assertStatus(200);
        $this->assertEquals("Success when creating.", $response['message']);
    }

    public function testError(): void
    {
        $this->customerService = Mockery::mock(CustomerService::class);
        $this->app->instance(CustomerService::class, $this->customerService);

        $this->customerService
            ->shouldReceive('create')
            ->once()
            ->with([
                'name' => 'Thiago Silva',
                'phone' => '5907007080',
                'email' => 'thiago.silva@gmail.com',
                'password' => 'thiago123'
            ])
            ->andThrow(new \Exception('General error'));

        $response = $this->postJson('/api/create-customer', [
            'name' => 'Thiago Silva',
            'phone' => '5907007080',
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiago123'
        ]);

        $response->assertStatus(400);       
        $this->assertEquals("Error when creating.", $response['message']); 
    }

    public function testEmailAlreadyRegistered(): void
    {
        $this->postJson('/api/create-customer', [
            'name' => 'Gabriel Carvalho',
            'phone' => '9007007080',
            'email' => 'thiago.silva@gmail.com',
            'password' => 'gabriel123'
        ]);

        $response = $this->postJson('/api/create-customer', [
            'name' => 'Thiago Silva',
            'phone' => '5907007080',
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiago123'
        ]);

        $response->assertStatus(400);
        $this->assertEquals("E-mail already registered.", $response['message']); 
    }

    public function testRequiredField(): void
    {
        $response = $this->postJson('/api/create-customer', [
            'phone' => '9007007080',
            'email' => 'thiago.silva@gmail.com',
            'password' => 'gabriel123'
        ]);
        
        $response->assertStatus(422); 
        $this->assertEquals("The name field is required.", $response['message']);
    }

    public function testCreatedTypeCustomer(): void
    {
        $this->postJson('/api/create-customer', [
            'name' => 'Gabriel Carvalho',
            'phone' => '9007007080',
            'email' => 'thiago.silva@gmail.com',
            'password' => 'gabriel123'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'thiago.silva@gmail.com',
            'type' => 'customer'
        ]);
    }

    public function testCreatedInTableUsers(): void
    {
        $this->postJson('/api/create-customer', [
            'name' => 'Gabriel Carvalho',
            'phone' => '9007007080',
            'email' => 'thiago.silva@gmail.com',
            'password' => 'gabriel123'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Gabriel Carvalho',
            'phone' => '9007007080',
            'email' => 'thiago.silva@gmail.com'
        ]);
    }
}
