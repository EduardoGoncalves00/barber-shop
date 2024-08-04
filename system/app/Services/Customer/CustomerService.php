<?php 

namespace App\Services\Customer;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class CustomerService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     * @return bool
     * @throws EmailAlreadyRegisteredException
     */
    public function create(array $data): bool
    {
        if ($this->validateEmailAlreadyRegistered($data['email'])) {
            throw new EmailAlreadyRegisteredException();
        }

        $data['password'] = Hash::make($data['password']);

        $data['type'] = 'customer';

        $this->userRepository->create($data);

        return true;
    }
    
    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $data['id'] = auth()->user()->id;

        if (isset($data['email']) && $this->validateEmailAlreadyRegistered($data['email'])) {
            throw new EmailAlreadyRegisteredException();
        }

        return $this->userRepository->update($data);
    }

    /**
     * @param string $email
     * @return bool
    */
    public function validateEmailAlreadyRegistered(string $email): bool
    {
        $emailRegistered = $this->userRepository->getByEmail($email);
        return $emailRegistered ? true : false;
    }
}