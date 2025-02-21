<?php

namespace App\Interfaces;

interface DocumentRepositoryInterface extends BaseRepositoryInterface
{
    public function getDocuments(?string $search, ?string $fileExtension);
}
