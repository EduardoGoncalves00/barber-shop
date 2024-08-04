<?php

namespace Tests\Feature\Customer;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Repositories\UserRepository;
use App\Services\Customer\CustomerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class CustomerUpdateTest extends TestCase
{
    protected $userRepository;
    protected $customerService;
    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = (object) [
            'id' => 1,
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'type' => 'customer'
        ];

        Auth::shouldReceive('user')->andReturn($this->customer);

        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->customerService = new CustomerService($this->userRepository);
    }

    public function testUpdateCustomer(): void
    {
        $this->userRepository
            ->shouldReceive('update')
            ->andReturn(true);
        
        $this->userRepository
            ->shouldReceive('getByEmail')
            ->with('eduardo@example.com')
            ->andReturn(false);

        $customerUpdated = $this->customerService->update([
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
            'password' => 'password'
        ]);

        $this->assertTrue($customerUpdated);
    }

    public function testUpdatePasswordCustomer(): void
    {
        $this->userRepository
            ->shouldReceive('update')
            ->andReturn(true);
        
        $this->userRepository
            ->shouldReceive('getByEmail')
            ->with('eduardo@example.com')
            ->andReturn(false);

        $customerUpdated = $this->customerService->update([
            'password' => 'password',
        ]);

        $this->assertTrue($customerUpdated);
    }

    public function testUpdateCustomerEmailRegistered(): void
    {
        $this->expectException(EmailAlreadyRegisteredException::class);
        
        $this->userRepository
            ->shouldReceive('getByEmail')
            ->with('eduardo@example.com')
            ->andReturn(true);

        $this->customerService->update([
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
            'password' => 'password'
        ]);
    }
}
