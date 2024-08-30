<?php 

namespace App\Services\Barber;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Repositories\BarbersWorkingHoursRepository;
use App\Repositories\UserRepository;

class BarberService
{
    protected $userRepository;
    protected $barbersWorkingHoursRepository;

    public function __construct(UserRepository $userRepository, BarbersWorkingHoursRepository $barbersWorkingHoursRepository)
    {
        $this->userRepository = $userRepository;
        $this->barbersWorkingHoursRepository = $barbersWorkingHoursRepository;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        if (isset($data['email'])) {
            $this->validateEmailAlreadyRegistered($data['email']);
        }

        $data['type'] = 'barber';
        
        $barber = $this->userRepository->create($data);

        $data['barber_id'] = $barber->id;

        $this->barbersWorkingHoursRepository->create($data);

        return true;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $data['id'] = auth()->user()->id;

        $this->userRepository->update($data);
        $this->barbersWorkingHoursRepository->update($data);

        return true;
    }

    /**
     * @param string $email
     * @return void
    */
    public function validateEmailAlreadyRegistered(string $email): void
    {
        $emailRegistered = $this->userRepository->getByEmail($email);

        if ($emailRegistered) {
            throw new EmailAlreadyRegisteredException();
        }
    }
}