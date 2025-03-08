<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Http\Resources\BusinessResource;
use App\Services\BusinessService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

/**
 * @OA\Tag(
 *     name="Business",
 *     description="Quản lý doanh nghiệp"
 * )
 */
class BusinessController extends Controller
{
    use ApiResponse;

    protected $businessService;

    public function __construct(BusinessService $businessService)
    {
        $this->businessService = $businessService;
    }

    /**
     * @OA\Get(
     *     path="/api/businesses",
     *     summary="Lấy danh sách doanh nghiệp",
     *     tags={"Businesses"},
     *     security={{"bearerAuth": {}}},    
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Tìm kiếm theo tên doanh nghiệp",
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
     *         description="Số lượng doanh nghiệp trên mỗi trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách doanh nghiệp",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Business"))
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $business = $this->businessService->getBusiness($request->search, $request->per_page);
        return $this->success(BusinessResource::collection($business), "Lấy danh sách doanh nghiệp thành công");
    }

    /**
     * @OA\Post(
     *     path="/api/businesses",
     *     summary="Thêm doanh nghiệp",
     *     tags={"Businesses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreBusinessRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="doanh nghiệp đã được tạo",
     *         @OA\JsonContent(ref="#/components/schemas/Business")
     *     )
     * )
     */
    public function store(StoreBusinessRequest $request): JsonResponse
    {
        $business = $this->businessService->createBusiness($request->validated());
        return $this->success(new BusinessResource($business), 'doanh nghiệp đã được tạo thành công!', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/businesses/{id}",
     *     summary="Lấy thông tin doanh nghiệp",
     *     tags={"Businesses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của doanh nghiệp",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin doanh nghiệp",
     *         @OA\JsonContent(ref="#/components/schemas/Business")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="doanh nghiệp không tồn tại"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $business = $this->businessService->getBusinessById($id);
        return $business ? $this->success(new BusinessResource($business)) : $this->error('doanh nghiệp không tồn tại!', 404);
    }

    /**
     * @OA\Put(
     *     path="/api/businesses/{id}",
     *     summary="Cập nhật doanh nghiệp",
     *     tags={"Businesses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của doanh nghiệp",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreBusinessRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật doanh nghiệp thành công",
     *         @OA\JsonContent(ref="#/components/schemas/Business")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="doanh nghiệp không tồn tại"
     *     )
     * )
     */
    public function update(UpdateBusinessRequest $request, $id): JsonResponse
    {
        $business = $this->businessService->updateBusiness($id, $request->validated());
        return $this->success(new BusinessResource($business), 'Cập nhật doanh nghiệp thành công!');
    }

    /**
     * @OA\Delete(
     *     path="/api/businesses/{id}",
     *     summary="Xóa doanh nghiệp",
     *     tags={"Businesses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của doanh nghiệp",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa doanh nghiệp thành công"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="doanh nghiệp không tồn tại hoặc không thể xóa"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        return $this->businessService->deleteBusiness($id)
            ? $this->success(null, 'Xóa doanh nghiệp thành công!')
            : $this->error('doanh nghiệp không tồn tại hoặc không thể xóa!', 404);
    }
}
