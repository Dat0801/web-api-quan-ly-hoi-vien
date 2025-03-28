<?php

namespace App\Services;

use App\Interfaces\TargetCustomerGroupRepositoryInterface;

class TargetCustomerGroupService
{
    protected $targetCustomerGroupRepository;

    public function __construct(TargetCustomerGroupRepositoryInterface $targetCustomerGroupRepository)
    {
        $this->targetCustomerGroupRepository = $targetCustomerGroupRepository;
    }

    public function getTargetCustomerGroup($search, $perPage)
    {
        return $this->targetCustomerGroupRepository->getTargetCustomerGroup($search, $perPage);
    }

    public function getTargetCustomerGroupById($id)
    {
        return $this->targetCustomerGroupRepository->findById($id);
    }

    public function createTargetCustomerGroup(array $data)
    {
        return $this->targetCustomerGroupRepository->create($data);
    }

    public function updateTargetCustomerGroup($id, array $data)
    {
        return $this->targetCustomerGroupRepository->update($id, $data);
    }

    public function deleteTargetCustomerGroup($id)
    {
        return $this->targetCustomerGroupRepository->delete($id);
    }
}
