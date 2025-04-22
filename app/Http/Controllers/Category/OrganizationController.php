<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Services\OrganizationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

/**
 * @OA\Tag(
 *     name="Organization",
 *     description="Quản lý tổ chức"
 * )
 */
class OrganizationController extends Controller
{
    use ApiResponse;

    protected $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    /**
     * @OA\Get(
     *     path="/api/organizations",
     *     summary="Lấy danh sách tổ chức",
     *     tags={"Organizations"},
     *     security={{"bearerAuth": {}}},    
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Tìm kiếm theo tên tổ chức",
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
     *         description="Số lượng tổ chức trên mỗi trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách tổ chức",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $organization = $this->organizationService->getOrganization($request->search, $request->per_page);
        $organization->setCollection(
            OrganizationResource::collection($organization->getCollection())->collection
        );
        return $this->success($organization, "Lấy danh sách tổ chức thành công");
    }

    /**
     * @OA\Post(
     *     path="/api/organizations",
     *     summary="Thêm tổ chức",
     *     tags={"Organizations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreOrganizationRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="tổ chức đã được tạo",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     )
     * )
     */
    public function store(StoreOrganizationRequest $request): JsonResponse
    {
        $organization = $this->organizationService->createOrganization($request->validated());
        return $this->success(new OrganizationResource($organization), 'tổ chức đã được tạo thành công!', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/{id}",
     *     summary="Lấy thông tin tổ chức",
     *     tags={"Organizations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của tổ chức",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin tổ chức",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="tổ chức không tồn tại"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $organization = $this->organizationService->getOrganizationById($id);
        return $organization ? $this->success(new OrganizationResource($organization)) : $this->error('tổ chức không tồn tại!', 404);
    }

    /**
     * @OA\Put(
     *     path="/api/organizations/{id}",
     *     summary="Cập nhật tổ chức",
     *     tags={"Organizations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của tổ chức",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreOrganizationRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật tổ chức thành công",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="tổ chức không tồn tại"
     *     )
     * )
     */
    public function update(UpdateOrganizationRequest $request, $id): JsonResponse
    {
        $organization = $this->organizationService->updateOrganization($id, $request->validated());
        return $this->success(new OrganizationResource($organization), 'Cập nhật tổ chức thành công!');
    }

    /**
     * @OA\Delete(
     *     path="/api/organizations/{id}",
     *     summary="Xóa tổ chức",
     *     tags={"Organizations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của tổ chức",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa tổ chức thành công"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="tổ chức không tồn tại hoặc không thể xóa"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        return $this->organizationService->deleteOrganization($id)
            ? $this->success(null, 'Xóa tổ chức thành công!')
            : $this->error('tổ chức không tồn tại hoặc không thể xóa!', 404);
    }
}
