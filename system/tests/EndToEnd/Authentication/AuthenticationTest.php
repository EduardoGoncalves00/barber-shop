<?php

namespace Tests\EndToEnd\Authentication;

use App\Repositories\UserRepository;
use App\Services\Authentication\AuthenticationService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $authenticationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = $this->postJson('/api/create-customer', [
            'name' => 'Thiago Silva',
            'phone' => '5907007080',
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiago123'
        ]);
    }

    public function testLoginSuccess(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiago123'
        ]);

        $response->assertStatus(200);
        $this->assertEquals("Success when logging in.", $response['message']);
        $this->assertNotEmpty($response['data']['token']);
    }

    public function testLoginCreatedTokenInTablePersonalAccessTokens(): void
    {
        $this->post('/api/login', [
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiago123'
        ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => app(UserRepository::class)->find(1)->id
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }
    

    public function testLoginError(): void
    {
        $this->authenticationService = Mockery::mock(AuthenticationService::class);
        $this->app->instance(AuthenticationService::class, $this->authenticationService);

        $this->authenticationService
            ->shouldReceive('login')
            ->once()
            ->with([
                'email' => 'thiago.silva@gmail.com',
                'password' => 'thiago123'
            ])
            ->andThrow(new \Exception('General error'));

        $response = $this->postJson('/api/login', [
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiago123'
        ]);

        $response->assertStatus(400);       
        $this->assertEquals("Error when logging in.", $response['message']); 
    }

    public function testIncorrectCredentials(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiaago123'
        ]);

        $response->assertStatus(400);
        $this->assertEquals("Incorrect credentials.", $response['message']);
    }

    public function testLogoutDeletedtokenInTablePersonalAccessTokens(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiago123'
        ]);

        $this->post('/api/logout', [], [
            'Authorization' => 'Bearer ' . $response['data']['token']
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function testLogoutSuccess(): void
    {
        $token = $this->post('/api/login', [
            'email' => 'thiago.silva@gmail.com',
            'password' => 'thiago123'
        ]);

        $response = $this->post('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token['data']['token']
        ]);

        $response->assertStatus(200);
        $this->assertEquals("Success when logout.", $response['message']);    
    }
}
