<?php

namespace App\Services;

use App\Interfaces\OrganizationRepositoryInterface;

class OrganizationService
{
    protected $organizationRepository;

    public function __construct(OrganizationRepositoryInterface $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function getOrganization($search, $perPage)
    {
        return $this->organizationRepository->getOrganization($search, $perPage);
    }

    public function getOrganizationById($id)
    {
        return $this->organizationRepository->findById($id);
    }

    public function createOrganization(array $data)
    {
        return $this->organizationRepository->create($data);
    }

    public function updateOrganization($id, array $data)
    {
        return $this->organizationRepository->update($id, $data);
    }

    public function deleteOrganization($id)
    {
        return $this->organizationRepository->delete($id);
    }
}
