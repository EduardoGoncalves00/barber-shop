<?php

namespace Tests\Feature\Authentication;

use App\Exceptions\IncorrectCredentialsException;
use App\Models\User;
use App\Services\Authentication\AuthenticationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $authenticationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'william@example.com',
            'password' => ('password'),
        ]);

        $this->authenticationService = new AuthenticationService();
    }

    public function testLoginWithValidCredentials()
    {
        $credentials = [
            'email' => 'william@example.com',
            'password' => 'password'
        ];

        $token = $this->authenticationService->login($credentials);

        $this->assertNotNull($token);
    }

    public function testLoginIncorrectCredentials(): void
    {
        $this->expectException(IncorrectCredentialsException::class);

        $credentials = [
            'email' => 'william@example.com',
            'password' => 'wrongpassword'
        ];

        $this->authenticationService->login($credentials);
    }
}
