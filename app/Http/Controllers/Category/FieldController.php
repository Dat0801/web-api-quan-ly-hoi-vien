<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Http\Resources\FieldResource;
use App\Http\Resources\PaginatedResourceCollection;
use App\Services\FieldService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

/**
 * @OA\Tag(
 *     name="Fields",
 *     description="Quản lý lĩnh vực"
 * )
 */
class FieldController extends Controller
{
    use ApiResponse;

    protected $fieldService;

    public function __construct(FieldService $fieldService)
    {
        $this->fieldService = $fieldService;
    }

    /**
     * @OA\Get(
     *     path="/api/fields",
     *     summary="Lấy danh sách lĩnh vực",
     *     tags={"Fields"},
     *     security={{"bearerAuth": {}}},    
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Tìm kiếm theo tên lĩnh vực",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Số trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Số lượng lĩnh vực trên mỗi trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách lĩnh vực",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Field"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);

        $fields = $this->fieldService->getFields($search, $perPage);

        $fields->setCollection(
            FieldResource::collection($fields->getCollection())->collection
        );

        return $this->success($fields, "Lấy danh sách lĩnh vực thành công");
    }

    /**
     * @OA\Post(
     *     path="/api/fields",
     *     summary="Thêm lĩnh vực",
     *     tags={"Fields"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreFieldRequest")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Lĩnh vực đã được tạo", @OA\JsonContent(ref="#/components/schemas/Field"))
     * )
     */
    public function store(StoreFieldRequest $request): JsonResponse
    {
        $field = $this->fieldService->createField($request->validated());
        return $this->success(new FieldResource($field), 'Lĩnh vực đã được tạo thành công!', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/fields/{id}",
     *     summary="Lấy thông tin lĩnh vực",
     *     tags={"Fields"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Chi tiết lĩnh vực", @OA\JsonContent(ref="#/components/schemas/Field"))
     * )
     */
    public function show($id): JsonResponse
    {
        $field = $this->fieldService->getFieldById($id);
        return $field ? $this->success(new FieldResource($field)) : $this->error('Lĩnh vực không tồn tại!', 404);
    }

    /**
     * @OA\Put(
     *     path="/api/fields/{id}",
     *     summary="Cập nhật lĩnh vực",
     *     tags={"Fields"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreFieldRequest")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật lĩnh vực thành công", @OA\JsonContent(ref="#/components/schemas/Field"))
     * )
     */
    public function update(UpdateFieldRequest $request, $id): JsonResponse
    {
        $field = $this->fieldService->updateField($id, $request->validated());
        return $this->success(new FieldResource($field), 'Cập nhật lĩnh vực thành công!');
    }

    /**
     * @OA\Delete(
     *     path="/api/fields/{id}",
     *     summary="Xóa lĩnh vực",
     *     tags={"Fields"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Xóa lĩnh vực thành công")
     * )
     */
    public function destroy($id): JsonResponse
    {
        return $this->fieldService->deleteField($id)
            ? $this->success(null, 'Xóa lĩnh vực thành công!')
            : $this->error('Lĩnh vực không tồn tại hoặc không thể xóa!', 404);
    }
}