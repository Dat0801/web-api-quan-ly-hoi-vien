<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndustryRequest;
use App\Http\Requests\UpdateIndustryRequest;
use App\Http\Resources\IndustryResource;
use App\Services\IndustryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

/**
 * @OA\Tag(
 *     name="Industries",
 *     description="Quản lý ngành nghề"
 * )
 */
class IndustryController extends Controller
{
    use ApiResponse;

    protected $industryService;

    public function __construct(IndustryService $industryService)
    {
        $this->industryService = $industryService;
    }

    /**
     * @OA\Get(
     *     path="/api/industries",
     *     summary="Lấy danh sách ngành nghề",
     *     tags={"Industries"},
     *     security={{"bearerAuth": {}}},    
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Tìm kiếm theo tên ngành nghề",
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
     *         description="Số lượng ngành nghề trên mỗi trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách ngành nghề",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Industry"))
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $industries = $this->industryService->getIndustries($request->search, $request->per_page);
        $industries->setCollection(
            IndustryResource::collection($industries->getCollection())->collection
        );
        return $this->success($industries, "Lấy danh sách ngành nghề thành công");
    }

    /**
     * @OA\Post(
     *     path="/api/industries",
     *     summary="Thêm ngành nghề",
     *     tags={"Industries"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreIndustryRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ngành nghề đã được tạo",
     *         @OA\JsonContent(ref="#/components/schemas/Industry")
     *     )
     * )
     */
    public function store(StoreIndustryRequest $request): JsonResponse
    {
        $industry = $this->industryService->createIndustry($request->validated());
        return $this->success(new IndustryResource($industry), 'Ngành nghề đã được tạo thành công!', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/industries/{id}",
     *     summary="Lấy thông tin ngành nghề",
     *     tags={"Industries"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của ngành nghề",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin ngành nghề",
     *         @OA\JsonContent(ref="#/components/schemas/Industry")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ngành nghề không tồn tại"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $industry = $this->industryService->getIndustryById($id);
        return $industry ? $this->success(new IndustryResource($industry)) : $this->error('Ngành nghề không tồn tại!', 404);
    }

    /**
     * @OA\Put(
     *     path="/api/industries/{id}",
     *     summary="Cập nhật ngành nghề",
     *     tags={"Industries"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của ngành nghề",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreIndustryRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật ngành nghề thành công",
     *         @OA\JsonContent(ref="#/components/schemas/Industry")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ngành nghề không tồn tại"
     *     )
     * )
     */
    public function update(UpdateIndustryRequest $request, $id): JsonResponse
    {
        $industry = $this->industryService->updateIndustry($id, $request->validated());
        return $this->success(new IndustryResource($industry), 'Cập nhật ngành nghề thành công!');
    }

    /**
     * @OA\Delete(
     *     path="/api/industries/{id}",
     *     summary="Xóa ngành nghề",
     *     tags={"Industries"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của ngành nghề",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa ngành nghề thành công"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ngành nghề không tồn tại hoặc không thể xóa"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        return $this->industryService->deleteIndustry($id)
            ? $this->success(null, 'Xóa ngành nghề thành công!')
            : $this->error('Ngành nghề không tồn tại hoặc không thể xóa!', 404);
    }
}
