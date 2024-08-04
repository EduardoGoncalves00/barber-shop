<?php
namespace Tests\Unit\Authentication;

use App\Exceptions\IncorrectCredentialsException;
use App\Models\User;
use App\Services\Authentication\AuthenticationService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    protected $authenticationService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = (object) [
            'id' => 1,
            'name' => 'Eduardo Boeira',
            'email' => 'eduardo@example.com',
            'type' => 'barber'
        ];

        Auth::shouldReceive('user')->andReturn($this->user);

        $this->authenticationService = Mockery::mock(AuthenticationService::class);
    }

    public function testLoginWithValidCredentials()
    {
        $credentials = [
            'email' => 'william@example.com',
            'password' => 'password'
        ];

        $this->authenticationService
            ->shouldReceive('login')
            ->andReturn('mocked-token');
            
        $token = $this->authenticationService->login($credentials);

        $this->assertEquals('mocked-token', $token);
    }

    public function testLoginIncorrectCredentials(): void
    {
        $this->expectException(IncorrectCredentialsException::class);

        $this->authenticationService
            ->shouldReceive('login')
            ->andThrow(new IncorrectCredentialsException());

        $credentials = [
            'email' => 'william@example.com',
            'password' => 'wrongpassword'
        ];
        
        $this->authenticationService->login($credentials);
    }
}
