<?php 

namespace App\Services\Authentication;

use App\Exceptions\IncorrectCredentialsException;

class AuthenticationService
{
    /**
     * @param array $credentials
     * @return mixed
     * 
     * @throws IncorrectCredentialsException
     */
    public function login(array $credentials): mixed
    {
        if (!auth()->attempt($credentials)) {
            throw new IncorrectCredentialsException();
        } 

        $user = auth()->user();
        return $user->createToken('authToken', ['*'], now()->addHours(4))->plainTextToken;
    }
    
    /**
     * @return void
     */
    public function logout(): void
    {
        auth()->user()->tokens()->delete();
    }
}