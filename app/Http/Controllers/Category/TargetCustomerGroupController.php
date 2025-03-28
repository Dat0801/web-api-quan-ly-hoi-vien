<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTargetCustomerGroupRequest;
use App\Http\Requests\UpdateTargetCustomerGroupRequest;
use App\Http\Resources\TargetCustomerGroupResource;
use App\Services\TargetCustomerGroupService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

/**
 * @OA\Tag(
 *     name="TargetCustomerGroups",
 *     description="Quản lý nhóm khách hàng"
 * )
 */
class TargetCustomerGroupController extends Controller
{
    use ApiResponse;

    protected $targetCustomerGroupService;

    public function __construct(TargetCustomerGroupService $targetCustomerGroupService)     
    {
        $this->targetCustomerGroupService = $targetCustomerGroupService;
    }

    /**
     * @OA\Get(
     *     path="/api/target-customer-groups",
     *     summary="Lấy danh sách nhóm khách hàng",
     *     tags={"TargetCustomerGroups"},
     *     security={{"bearerAuth": {}}},    
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Tìm kiếm theo tên nhóm khách hàng",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Số trang",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Số lượng nhóm khách hàng trên mỗi trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách nhóm khách hàng",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/TargetCustomerGroup"))
     *     )
     * )
     */
    public function index(Request $request): JsonResponse       
    {
        $targetCustomerGroup = $this->targetCustomerGroupService->getTargetCustomerGroup($request->search, $request->per_page);
        return $this->success(TargetCustomerGroupResource::collection($targetCustomerGroup), "Lấy danh sách nhóm khách hàng thành công");
    }

    /**
     * @OA\Post(
     *     path="/api/target-customer-groups",
     *     summary="Thêm nhóm khách hàng",
     *     tags={"TargetCustomerGroups"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreTargetCustomerGroupRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="nhóm khách hàng đã được tạo",
     *         @OA\JsonContent(ref="#/components/schemas/TargetCustomerGroup")
     *     )
     * )
     */
    public function store(StoreTargetCustomerGroupRequest $request): JsonResponse
    {
        $targetCustomerGroup = $this->targetCustomerGroupService->createTargetCustomerGroup($request->validated());
        return $this->success(new TargetCustomerGroupResource($targetCustomerGroup), 'nhóm khách hàng đã được tạo thành công!', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/target-customer-groups/{id}",
     *     summary="Lấy thông tin nhóm khách hàng",
     *     tags={"TargetCustomerGroups"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của nhóm khách hàng",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin nhóm khách hàng",
     *         @OA\JsonContent(ref="#/components/schemas/TargetCustomerGroup")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="nhóm khách hàng không tồn tại"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $targetCustomerGroup = $this->targetCustomerGroupService->getTargetCustomerGroupById($id);
        return $targetCustomerGroup ? $this->success(new TargetCustomerGroupResource($targetCustomerGroup)) : $this->error('nhóm khách hàng không tồn tại!', 404);
    }

    /**
     * @OA\Put(
     *     path="/api/target-customer-groups/{id}",
     *     summary="Cập nhật nhóm khách hàng",
     *     tags={"TargetCustomerGroups"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của nhóm khách hàng",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreTargetCustomerGroupRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật nhóm khách hàng thành công",
     *         @OA\JsonContent(ref="#/components/schemas/TargetCustomerGroup")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="nhóm khách hàng không tồn tại"
     *     )
     * )
     */
    public function update(UpdateTargetCustomerGroupRequest $request, $id): JsonResponse
    {
        $targetCustomerGroup = $this->targetCustomerGroupService->updateTargetCustomerGroup($id, $request->validated());
        return $this->success(new TargetCustomerGroupResource($targetCustomerGroup), 'Cập nhật nhóm khách hàng thành công!');
    }

    /**
     * @OA\Delete(
     *     path="/api/target-customer-groups/{id}",
     *     summary="Xóa nhóm khách hàng",
     *     tags={"TargetCustomerGroups"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của nhóm khách hàng",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa nhóm khách hàng thành công"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="nhóm khách hàng không tồn tại hoặc không thể xóa"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        return $this->targetCustomerGroupService->deleteTargetCustomerGroup($id)
            ? $this->success(null, 'Xóa nhóm khách hàng thành công!')
            : $this->error('nhóm khách hàng không tồn tại hoặc không thể xóa!', 404);
    }
}
