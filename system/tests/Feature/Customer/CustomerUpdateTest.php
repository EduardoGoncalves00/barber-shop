<?php

namespace Tests\Feature\Customer;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Customer\CustomerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CustomerUpdateTest extends TestCase
{
    use RefreshDatabase;
    
    protected $customer;
    protected $customerService;

    protected function setUp(): void
    {
        parent::setUp();

        $userRepository = new UserRepository();
        $this->customerService = new CustomerService($userRepository);

        $this->customer = User::factory()->create();
    }

    public function testUpdateCustomer(): void
    {
        Sanctum::actingAs($this->customer);
    
        $customerUpdated = $this->customerService->update([
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
            'password' => 'password'
        ]);

        $this->assertTrue($customerUpdated);
    }

    public function testExpectsToUpatedInUserTable(): void
    {
        Sanctum::actingAs($this->customer);
    
        $customerUpdated = $this->customerService->update([
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
            'password' => 'password'
        ]);

        $this->assertTrue($customerUpdated);

        $this->assertDatabaseHas('users', [
            'id' => $this->customer->id,
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'phone' => '(51) 8888-8888',
        ]);
    }

    // public function testUpdateCustomerEmailRegistered(): void
    // {
    //     $this->expectException(EmailAlreadyRegisteredException::class);

    //     Sanctum::actingAs($this->customer);

    //     $this->customerService->create([
    //         'name' =>' Eduardo Boeira',
    //         'email' => 'eduardo@example.com',
    //         'phone' => '(51) 8888-8888',
    //         'password' => 'password'
    //     ]);

    //     $this->customerService->update([
    //         'email' => 'eduardo@example.com',
    //     ]);
    // }
}