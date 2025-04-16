<?php
namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UserService
{
    protected UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getUsers($status, $roleId, $search)
    {
        return $this->userRepo->getUsers($status, $roleId, $search);
    }

    public function getUserById($id)
    {
        return $this->userRepo->findById($id);
    }


    public function createUser(array $data)
    {
        if (isset($data['status'])) {
            $data['status'] = filter_var($data['status'], FILTER_VALIDATE_BOOLEAN);
        }

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return $this->userRepo->create($data);
    }


    public function updateUser(int $id, array $data)
    {
        $user = $this->userRepo->findById($id);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->userRepo->update($id, $data);
    }

    public function deleteUser(int $id)
    {
        $user = $this->userRepo->findById($id);

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        return $this->userRepo->delete($id);
    }
}
