<?php

namespace App\Interfaces;

interface DocumentRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllDocuments(?string $search, ?string $fileExtension);
}
