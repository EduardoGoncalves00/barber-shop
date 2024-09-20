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
            return ['customer-update', 'ability:customer-get-available-times-of-barber', 'ability:logout'];
        }
        //  'ability:customer-make-reservation'
        if ($user->type === "barber") {
            return ['ability:customer-update', 'ability:logout', 'ability:customer-service-type-index', 'ability:customer-service-type-create', 'ability:customer-service-type-delete', 'ability:customer-service-type-update'];
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