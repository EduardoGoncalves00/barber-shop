<?php

namespace Tests\Integration\Customer;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Repositories\UserRepository;
use App\Services\Customer\CustomerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCreateTest extends TestCase
{
    use RefreshDatabase;
    
    protected $customerService;

    protected function setUp(): void
    {
        parent::setUp();

        $userRepository = new UserRepository();
        $this->customerService = new CustomerService($userRepository);
    }

    public function testCreateCustomer(): void
    {    
        $customerCreated = $this->customerService->create([
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
            'password' => 'password'
        ]);

        $this->assertTrue($customerCreated);
    }

    public function testCreateCustomerExpectedTypeCustomer(): void
    {    
        $this->customerService->create([
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
            'password' => 'password'
        ]);

        $customerType = app(UserRepository::class)->getByEmail('eduardo@example.com');
        
        $this->assertEquals('customer', $customerType->type);
    }

    public function testCreateCustomerEmailRegistered(): void
    {
        $this->expectException(EmailAlreadyRegisteredException::class);

        $this->customerService->create([
            'name' =>' Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
            'password' => 'password'
        ]);

        $this->customerService->create([
            'email' => 'eduardo@example.com',
        ]);
    }
}