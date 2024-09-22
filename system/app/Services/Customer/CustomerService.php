<?php 

namespace App\Services\Customer;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Repositories\BarberScheduleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class CustomerService
{
    protected $userRepository;
    protected $barberScheduleRepository;

    public function __construct(UserRepository $userRepository, BarberScheduleRepository $barberScheduleRepository)
    {
        $this->userRepository = $userRepository;
        $this->barberScheduleRepository = $barberScheduleRepository;
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

        return $this->userRepository->update($data);
    }

    /**
     * @return mixed
     */
    public function getReserve(): mixed
    {
        $id = auth()->user()->id;

        return $this->barberScheduleRepository->getReserveByCustomer($id);
    }

    /**
     * @param string $email
     * @return bool
    */
    private function validateEmailAlreadyRegistered(string $email): bool
    {
        $emailRegistered = $this->userRepository->getByEmail($email);
        return $emailRegistered ? true : false;
    }
}