<?php

namespace App\Interfaces;
use App\Interfaces\BaseRepositoryInterface;

interface IndustryRepositoryInterface extends BaseRepositoryInterface
{
    public function getIndustries($search, $perPage);
}
