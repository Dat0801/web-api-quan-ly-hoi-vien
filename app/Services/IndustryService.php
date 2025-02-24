<?php

namespace App\Services;

use App\Interfaces\IndustryRepositoryInterface;

class IndustryService
{
    protected $industryRepository;

    public function __construct(IndustryRepositoryInterface $industryRepository)
    {
        $this->industryRepository = $industryRepository;
    }

    public function getIndustries($search, $perPage)
    {
        return $this->industryRepository->getIndustries($search, $perPage);
    }

    public function getIndustryById($id)
    {
        return $this->industryRepository->findById($id);
    }

    public function createIndustry(array $data)
    {
        return $this->industryRepository->create($data);
    }

    public function updateIndustry($id, array $data)
    {
        return $this->industryRepository->update($id, $data);
    }

    public function deleteIndustry($id)
    {
        return $this->industryRepository->delete($id);
    }
}
