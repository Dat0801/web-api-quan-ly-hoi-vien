<?php

namespace App\Interfaces;

interface MarketRepositoryInterface extends BaseRepositoryInterface
{
    public function getMarkets($search, $perPage);
}
