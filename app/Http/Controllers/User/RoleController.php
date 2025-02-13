<?php 
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

class RoleController extends Controller
{
    use ApiResponse; 

    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(Request $request): JsonResponse
    {
        $roles = $this->roleService->getAllRoles($request->get('search'));
        return $this->success(RoleResource::collection($roles));
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->createRole($request->validated());
        return $this->success(new RoleResource($role), 'Vai trò đã được tạo thành công!', 201);
    }

    public function show($id): JsonResponse
    {
        $role = $this->roleService->getRoleById($id);
        return $role ? $this->success(new RoleResource($role)) : $this->error('Vai trò không tồn tại!', 404);
    }

    public function update(UpdateRoleRequest $request, $id): JsonResponse
    {
        $role = $this->roleService->updateRole($id, $request->validated());
        return $this->success(new RoleResource($role), 'Cập nhật vai trò thành công!');
    }

    public function destroy($id): JsonResponse
    {
        return $this->roleService->deleteRole($id)
            ? $this->success(null, 'Xóa vai trò thành công!')
            : $this->error('Vai trò không tồn tại hoặc không thể xóa!', 404);
    }
}
