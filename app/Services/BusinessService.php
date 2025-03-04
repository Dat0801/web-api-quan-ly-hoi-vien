<?php

namespace App\Services;

use App\Interfaces\BusinessRepositoryInterface;

class BusinessService
{
    protected $businessRepository;

    public function __construct(BusinessRepositoryInterface $businessRepository)
    {
        $this->businessRepository = $businessRepository;
    }

    public function getBusiness($search, $perPage)
    {
        return $this->businessRepository->getBusiness($search, $perPage);
    }

    public function getBusinessById($id)
    {
        return $this->businessRepository->findById($id);
    }

    public function createBusiness(array $data)
    {
        return $this->businessRepository->create($data);
    }

    public function updateBusiness($id, array $data)
    {
        return $this->businessRepository->update($id, $data);
    }

    public function deleteBusiness($id)
    {
        return $this->businessRepository->delete($id);
    }
}
