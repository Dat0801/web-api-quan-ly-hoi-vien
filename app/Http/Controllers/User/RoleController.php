<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->get('search');

        $roles = Role::with('permissions')
            ->when($search, function ($query, $search) {
                return $query->where('role_name', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        return view('user.role.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy('group_name');

        return view('user.role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'role_name' => 'required|string|max:255|unique:roles,role_name',
            'role_id' => 'required|string|max:255|unique:roles,role_id',
            'permissions' => 'array',
        ], [
            'role_name.required' => 'Tên vai trò không được để trống.',
            'role_name.unique' => 'Tên vai trò đã tồn tại.',
            'role_id.required' => 'Mã vai trò không được để trống.',
            'role_id.unique' => 'Mã vai trò đã tồn tại.',
            'permissions.array' => 'Danh sách quyền không hợp lệ.',
        ]);

        try {
            $role = Role::create([
                'role_name' => $validatedData['role_name'],
                'role_id' => $validatedData['role_id'],
            ]);

            if (isset($validatedData['permissions']) && is_array($validatedData['permissions'])) {
                $existingPermissions = Permission::pluck('id')->toArray();
                $newPermissions = [];

                foreach ($validatedData['permissions'] as $permissionId) {
                    $groupID = explode('.', $permissionId)[0];
                    if (!in_array($permissionId, $existingPermissions)) {
                        $newPermission = Permission::create([
                            'permission_name' => "Chức năng $permissionId",
                            'group_name' => "Nhóm chức năng $groupID",
                        ]);
                        $newPermissions[] = $newPermission->id;
                    } else {
                        $newPermissions[] = $permissionId;
                    }
                }

                $currentPermissions = $role->permissions->pluck('id')->toArray();
                $uniquePermissions = array_diff($newPermissions, $currentPermissions);

                $role->permissions()->syncWithoutDetaching($uniquePermissions);
            }

            return redirect()->route('role.index')
                ->with('success', 'Vai trò đã được thêm thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Đã xảy ra lỗi trong quá trình lưu vai trò: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show($id)
    {
        $role = Role::with('permissions', 'users')->findOrFail($id);

        $permissionsByGroup = Permission::all()->groupBy('group_name');

        return view('user.role.show', compact('role', 'permissionsByGroup'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all()->groupBy('group_name'); 
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('user.role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'role_id' => 'required|string|max:50|unique:roles,role_id,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $role = Role::findOrFail($id);

        $role->update([
            'role_name' => $request->input('role_name'),
            'role_id' => $request->input('role_id'),
        ]);

        if ($request->has('permissions')) {
            $submittedPermissions = $request->input('permissions');
            $existingPermissions = Permission::pluck('id')->toArray(); 
            $validPermissions = [];

            foreach ($submittedPermissions as $permissionId) {
                if (!in_array($permissionId, $existingPermissions)) {
                    $groupID = explode('.', $permissionId)[0];

                    $newPermission = Permission::create([
                        'permission_name' => "Chức năng $permissionId",
                        'group_name' => "Nhóm chức năng $groupID",
                    ]);
                    $newPermissions[] = $newPermission->id;
                    $validPermissions[] = $newPermission->id;
                } else {
                    $validPermissions[] = $permissionId;
                }
            }

            $role->permissions()->sync($validPermissions);
        } else {
            $role->permissions()->detach();
        }

        return redirect()->route('role.index')->with('success', 'Cập nhật vai trò thành công');
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            $role->permissions()->detach();

            $role->delete();

            return redirect()->route('role.index')->with('success', 'Xóa vai trò thành công');
        } catch (\Exception $e) {
            return redirect()->route('role.index')->withErrors(['error' => 'Error deleting role: ' . $e->getMessage()]);
        }
    }
}
