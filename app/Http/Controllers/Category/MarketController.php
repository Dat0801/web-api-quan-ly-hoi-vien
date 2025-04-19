<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMarketRequest;
use App\Http\Requests\UpdateMarketRequest;
use App\Http\Resources\MarketResource;
use App\Services\MarketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\Market;

/**
 * @OA\Tag(
 *     name="Markets",
 *     description="Quản lý thị trường"
 * )
 */
class MarketController extends Controller
{
    use ApiResponse;

    protected $marketService;

    public function __construct(MarketService $marketService)
    {
        $this->marketService = $marketService;
    }

    /**
     * @OA\Get(
     *     path="/api/markets",
     *     summary="Lấy danh sách thị trường",
     *     tags={"Markets"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Tìm kiếm theo tên thị trường",
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
     *         description="Số lượng thị trường trên mỗi trang",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách thị trường có phân trang",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Market")),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $markets = $this->marketService->getMarkets(
            $request->get('search'),
            $request->get('per_page', 10)
        );
        return $this->success($markets, "Lấy danh sách thị trường thành công");
    }

    /**
     * @OA\Post(
     *     path="/api/markets",
     *     summary="Thêm mới thị trường",
     *     tags={"Markets"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreMarketRequest")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Thị trường đã được tạo", @OA\JsonContent(ref="#/components/schemas/Market"))
     * )
     */
    public function store(StoreMarketRequest $request): JsonResponse
    {
        $market = $this->marketService->createMarket($request->validated());
        return $this->success(new MarketResource($market), 'Thêm thị trường thành công!', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/markets/{id}",
     *     summary="Lấy thông tin chi tiết thị trường",
     *     tags={"Markets"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Thông tin thị trường", @OA\JsonContent(ref="#/components/schemas/Market")),
     *     @OA\Response(response=404, description="Không tìm thấy thị trường")
     * )
     */
    public function show($id): JsonResponse
    {
        $market = $this->marketService->getMarketById($id);
        return $market ? $this->success(new MarketResource($market)) : $this->error('Thị trường không tồn tại!', 404);
    }

    /**
     * @OA\Put(
     *     path="/api/markets/{id}",
     *     summary="Cập nhật thông tin thị trường",
     *     tags={"Markets"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreMarketRequest")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật thành công", @OA\JsonContent(ref="#/components/schemas/Market")),
     *     @OA\Response(response=404, description="Không tìm thấy thị trường")
     * )
     */
    public function update(UpdateMarketRequest $request, $id): JsonResponse
    {
        $market = $this->marketService->updateMarket($id, $request->validated());
        return $this->success(new MarketResource($market), 'Cập nhật thị trường thành công!');
    }

    /**
     * @OA\Delete(
     *     path="/api/markets/{id}",
     *     summary="Xóa thị trường",
     *     tags={"Markets"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Xóa thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy thị trường")
     * )
     */
    public function destroy(Market $market): JsonResponse
    {
        $this->marketService->deleteMarket($market->id);
        return $this->success(null, 'Xóa thị trường thành công!');
    }
}
