<?php

namespace App\Repositories;

use App\Models\Market;
use App\Interfaces\MarketRepositoryInterface;

class MarketRepository extends BaseRepository implements MarketRepositoryInterface
{
    public function __construct(Market $model)
    {
        parent::__construct($model);
    }
    public function getMarkets($search, $perPage)
    {
        return Market::when($search, function ($query, $search) {
            return $query->where('market_name', 'LIKE', '%' . $search . '%');
        })->paginate($perPage);
    }

}
