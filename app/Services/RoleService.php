<?php
namespace App\Services;

use App\Interfaces\RoleRepositoryInterface;
use App\Repositories\PermissionRepository;

class RoleService
{
    protected $roleRepository;
    protected $permissionRepository;

    public function __construct(RoleRepositoryInterface $roleRepository, PermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function getRoles($search = null)
    {
        return $this->roleRepository->getRoles($search);
    }

    public function createRole(array $data)
    {
        $role = $this->roleRepository->create([
            'role_name' => $data['role_name'],
            'role_id'   => $data['role_id'],
        ]);

        if (!empty($data['permissions'])) {
            $permissions = $this->handlePermissions($data['permissions']);
            $role->permissions()->sync($permissions);
        }

        return $role;
    }

    private function handlePermissions(array $permissions)
    {
        $newPermissions = [];

        foreach ($permissions as $permissionId) {
            $existingPermission = $this->permissionRepository->findByName("Chức năng $permissionId");

            if (!$existingPermission) {
                $newPermission = $this->permissionRepository->create([
                    "Chức năng $permissionId",
                    "Nhóm chức năng " . explode('.', $permissionId)[0]]
                );
                $newPermissions[] = $newPermission->id;
            } else {
                $newPermissions[] = $existingPermission->id;
            }
        }

        return $newPermissions;
    }

    public function getRoleById($id)
    {
        return $this->roleRepository->findById($id);
    }

    public function updateRole($id, array $data)
    {
        $role = $this->roleRepository->update($id, $data);

        if (isset($data['permissions'])) {
            $permissions = $this->handlePermissions($data['permissions']);
            $role->permissions()->sync($permissions);
        }

        return $role;
    }

    public function deleteRole($id)
    {
        return $this->roleRepository->delete($id);
    }
}