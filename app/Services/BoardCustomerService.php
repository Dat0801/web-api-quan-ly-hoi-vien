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
        if (isset($data['avatar'])) {
            $file = $data['avatar'];
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $data['avatar'] = $file->storeAs('avatars', $fileName, 'public');
        }

        return $this->boardCustomerRepo->create($data);
    }

    public function updateCustomer($id, $data)
    {
        $customer = $this->boardCustomerRepo->findById($id);

        if (isset($data['avatar'])) {
            if ($customer->avatar) {
                Storage::disk('public')->delete($customer->avatar);
            }
            $file = $data['avatar'];
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $data['avatar'] = $file->storeAs('avatars', $fileName, 'public');
        }

        return $this->boardCustomerRepo->update($id, $data);
    }

    public function deleteCustomer($id)
    {
        return $this->boardCustomerRepo->delete($id);
    }
}
