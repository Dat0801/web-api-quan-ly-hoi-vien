<?php
namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class UserService
{
    protected UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getAllUsers($status, $roleId, $search)
    {
        return $this->userRepo->getAllUsers($status, $roleId, $search);
    }

    public function getUserById($id)
    {
        return $this->userRepo->findById($id);
    }

    public function createUser(array $data)
    {
        $fieldMapping = [
            'roleId' => 'role_id',
            'phoneNumber' => 'phone_number'
        ];

        foreach ($fieldMapping as $old => $new) {
            if (isset($data[$old])) {
                $data[$new] = $data[$old];
                unset($data[$old]);
            }
        }

        if (isset($data['status'])) {
            $data['status'] = filter_var($data['status'], FILTER_VALIDATE_BOOLEAN);
        }

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        if (!empty($data['avatar'])) {
            $file = $data['avatar'];
            $uniqueFileName = uniqid() . '_' . $file->getClientOriginalName();
            $data['avatar'] = $file->storeAs('avatars', $uniqueFileName, 'public');
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

        if (!empty($data['avatar'])) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file = $data['avatar'];
            $uniqueFileName = uniqid() . '_' . $file->getClientOriginalName();
            $data['avatar'] = $file->storeAs('avatars', $uniqueFileName, 'public');
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
