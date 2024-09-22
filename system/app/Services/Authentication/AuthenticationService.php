<?php 

namespace App\Services\Authentication;

use App\Exceptions\IncorrectCredentialsException;
use App\Models\User;

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
        $abilities = $this->getUserAbilities($user);

        return $user->createToken('authToken', $abilities, now()->addHours(4))->plainTextToken;
    }

    /**
     * @param User $user
     * @return array
     */
    private function getUserAbilities(User $user): array
    {   
        if ($user->type === "customer") {
            return [
                'customer-update', 
                'customer-get-available-times-of-barber', 
                'customer-make-reserve', 
                'customer-get-my-reserve',
                'logout'
            ];
        }

        if ($user->type === "barber") {
            return [
                'barber-update', 
                'service-type-index', 
                'service-type-create', 
                'service-type-delete', 
                'service-type-update',
                'logout'
            ];
        }

        return [];
    }
    
    /**
     * @return void
     */
    public function logout(): void
    {
        auth()->user()->tokens()->delete();
    }
}