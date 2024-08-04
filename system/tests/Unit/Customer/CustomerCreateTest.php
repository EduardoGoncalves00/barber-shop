<?php

namespace Tests\Unit\Customer;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Customer\CustomerService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class CustomerCreateTest extends TestCase
{
    protected $customerService;
    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->customerService = new CustomerService($this->userRepository);
    }

    public function testCreateCustomer(): void
    {
        $this->userRepository
            ->shouldReceive('create')
            ->andReturn();

        $this->userRepository
            ->shouldReceive('getByEmail')
            ->with('william@example.com')
            ->andReturn(false);

        $customer = $this->customerService->create([
            'name' =>' William Boeira',
            'email' => 'william@example.com',
            'phone' => '(51) 9999-9999',
            'password' => 'password',
            'start_lunch' => '12:00',
            'end_lunch' => '13:00',
            'start_work' => '08:00',
            'end_work' => '19:00'
        ]);

        $this->assertTrue($customer);
    }

    public function testCreateCustomerExpectedTypeCustomer(): void
    {
        $this->userRepository
            ->shouldReceive('create')
            ->andReturn();
        
        $this->userRepository
            ->shouldReceive('getByEmail')
            ->with('william@example.com')
            ->andReturn(false);

        $customer = $this->customerService->create([
            'name' =>' William Boeira',
            'email' => 'william@example.com',
            'phone' => '(51) 9999-9999',
            'password' => 'password'
        ]);

        $this->userRepository
            ->shouldHaveReceived('create')
            ->with(Mockery::on(function ($data) {
                return $data['type'] === 'customer';
        }));
    }

    public function testCreateEmailRegistered(): void
    {
        $this->expectException(EmailAlreadyRegisteredException::class);

        $this->userRepository
            ->shouldReceive('getByEmail')
            ->with('william@example.com')
            ->andReturn(true);

        $this->customerService->create([
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
