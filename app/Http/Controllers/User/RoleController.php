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

/**
 * @OA\Tag(
 *     name="Roles",
 *     description="Quản lý vai trò"
 * )
 */
class RoleController extends Controller
{
    use ApiResponse; 

    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * @OA\Get(
     *     path="/api/roles",
     *     summary="Lấy danh sách vai trò",
     *     tags={"Roles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Danh sách vai trò", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Role")))
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $roles = $this->roleService->getRoles($request->get('search'));
        $roles->setCollection(
            RoleResource::collection($roles->getCollection())->collection
        );
        return $this->success($roles, "Lấy danh sách vai trò thành công");
    }

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     summary="Thêm mới vai trò",
     *     tags={"Roles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreRoleRequest")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Vai trò đã được tạo", @OA\JsonContent(ref="#/components/schemas/Role"))
     * )
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->createRole($request->validated());
        return $this->success(new RoleResource($role), 'Vai trò đã được tạo thành công!', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     summary="Lấy thông tin vai trò",
     *     tags={"Roles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Thông tin vai trò", @OA\JsonContent(ref="#/components/schemas/Role"))
     * )
     */
    public function show($id): JsonResponse
    {
        $role = $this->roleService->getRoleById($id);
        return $role ? $this->success(new RoleResource($role)) : $this->error('Vai trò không tồn tại!', 404);
    }

    /**
     * @OA\Put(
     *     path="/api/roles/{id}",
     *     summary="Cập nhật vai trò",
     *     tags={"Roles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreRoleRequest")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật vai trò thành công", @OA\JsonContent(ref="#/components/schemas/Role"))
     * )
     */
    public function update(UpdateRoleRequest $request, $id): JsonResponse
    {
        $role = $this->roleService->updateRole($id, $request->validated());
        return $this->success(new RoleResource($role), 'Cập nhật vai trò thành công!');
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}",
     *     summary="Xóa vai trò",
     *     tags={"Roles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Xóa vai trò thành công")
     * )
     */
    public function destroy($id): JsonResponse
    {
        return $this->roleService->deleteRole($id)
            ? $this->success(null, 'Xóa vai trò thành công!')
            : $this->error('Vai trò không tồn tại hoặc không thể xóa!', 404);
    }
}
