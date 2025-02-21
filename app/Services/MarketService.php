<?php

namespace App\Services;

use App\Interfaces\MarketRepositoryInterface;

class MarketService
{
    protected $marketRepository;

    public function __construct(MarketRepositoryInterface $marketRepository)
    {
        $this->marketRepository = $marketRepository;
    }

    public function getMarkets($search, $perPage)
    {
        return $this->marketRepository->getMarkets($search, $perPage);
    }

    public function getMarketById($id)
    {
        return $this->marketRepository->findById($id);
    }

    public function createMarket(array $data)
    {
        return $this->marketRepository->create($data);
    }

    public function updateMarket($id, array $data)
    {
        return $this->marketRepository->update($id, $data);
    }

    public function deleteMarket($id)
    {
        return $this->marketRepository->delete($id);
    }
}
