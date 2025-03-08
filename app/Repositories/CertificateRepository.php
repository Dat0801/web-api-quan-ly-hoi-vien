<?php

namespace App\Repositories;

use App\Interfaces\CertificateRepositoryInterface;
use App\Models\Certificate;

class CertificateRepository extends BaseRepository implements CertificateRepositoryInterface
{
    public function __construct(Certificate $model)
    {
        parent::__construct($model);
    }

    public function getCertificate(?string $search, int $perPage = 10)
    {
        $query = Certificate::query();

        if (!empty($search)) {
            $query->where('certificate_name', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }
}
