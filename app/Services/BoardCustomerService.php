<?php

namespace App\Services;

use App\Interfaces\BoardCustomerRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class BoardCustomerService
{
    protected $boardCustomerRepo;

    public function __construct(BoardCustomerRepositoryInterface $boardCustomerRepo)
    {
        $this->boardCustomerRepo = $boardCustomerRepo;
    }

    public function getBoardCustomers($search, $perPage, $status)
    {
        return $this->boardCustomerRepo->getBoardCustomers($search, $perPage, $status);
    }

    public function findCustomer($id)
    {
        return $this->boardCustomerRepo->findById($id);
    }

    public function createCustomer($data)
    {
        return $this->boardCustomerRepo->create($data);
    }

    public function updateCustomer($id, $data)
    {
        return $this->boardCustomerRepo->update($id, $data);
    }

    public function deleteCustomer($id)
    {
        return $this->boardCustomerRepo->delete($id);
    }
}
