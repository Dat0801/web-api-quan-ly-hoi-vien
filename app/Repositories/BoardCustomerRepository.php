<?php

namespace App\Repositories;

use App\Models\BoardCustomer;
use App\Interfaces\BoardCustomerRepositoryInterface;

class BoardCustomerRepository extends BaseRepository implements BoardCustomerRepositoryInterface
{
    public function __construct(BoardCustomer $model)
    {
        parent::__construct($model);
    }
    public function getBoardCustomers($search, $perPage, $status)
    {
        return BoardCustomer::whereNull('club_id')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('login_code', 'like', "%{$search}%")
                        ->orWhere('card_code', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status === 'active' ? 1 : 0);
            })
            ->paginate($perPage);
    }

}
