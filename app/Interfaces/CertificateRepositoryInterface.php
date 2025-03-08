<?php

namespace App\Interfaces;
use App\Interfaces\BaseRepositoryInterface;

interface CertificateRepositoryInterface extends BaseRepositoryInterface
{
    public function getCertificate(?string $search, int $perPage);
}
