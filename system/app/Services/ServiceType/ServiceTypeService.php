<?php 

namespace App\Services\ServiceType;

use App\Exceptions\ServiceTypeDoesNotExistException;
use App\Repositories\ServiceTypeRepository;

class ServiceTypeService
{
    protected $serviceTypeRepository;

    public function __construct(ServiceTypeRepository $serviceTypeRepository)
    {
        $this->serviceTypeRepository = $serviceTypeRepository;
    }

    /**
     * @return array
     */
    public function index(): array
    {
        return $this->serviceTypeRepository->index()->toArray();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        $this->serviceTypeRepository->create($data);

        return true;
    }

    /**
     * @param array $data
     * @return mixed
     * 
     * @throws ServiceTypeDoesNotExistException
     */
    public function update(array $data): mixed
    {
        $service = $this->serviceTypeRepository->find($data['id']);

        if(!$service) {
            throw new ServiceTypeDoesNotExistException();
        };

        return $this->serviceTypeRepository->update($data);
    }

    /**
     * @param int $id
     * @return mixed
     * 
     * @throws ServiceTypeDoesNotExistException
     */
    public function delete(int $id): mixed
    {
        $service = $this->serviceTypeRepository->find($id);

        if(!$service) {
            throw new ServiceTypeDoesNotExistException();
        };

        return $this->serviceTypeRepository->delete($id);
    }
}