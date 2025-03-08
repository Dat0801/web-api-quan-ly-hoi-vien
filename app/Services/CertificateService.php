<?php

namespace App\Services;

use App\Interfaces\CertificateRepositoryInterface;

class CertificateService
{
    protected $certificateRepository;

    public function __construct(CertificateRepositoryInterface $certificateRepository)
    {
        $this->certificateRepository = $certificateRepository;
    }

    public function getCertificate($search, $perPage)
    {
        return $this->certificateRepository->getCertificate($search, $perPage);
    }

    public function getCertificateById($id)
    {
        return $this->certificateRepository->findById($id);
    }

    public function createCertificate(array $data)
    {
        return $this->certificateRepository->create($data);
    }

    public function updateCertificate($id, array $data)
    {
        return $this->certificateRepository->update($id, $data);
    }

    public function deleteCertificate($id)
    {
        return $this->certificateRepository->delete($id);
    }
}
