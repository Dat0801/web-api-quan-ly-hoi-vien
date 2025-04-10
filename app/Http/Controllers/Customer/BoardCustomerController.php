<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBoardCustomerRequest;
use App\Http\Requests\UpdateBoardCustomerRequest;
use App\Services\BoardCustomerService;
use App\Http\Resources\BoardCustomerResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Board Customers", description="Quản lý ban chấp hành")
 */
class BoardCustomerController extends Controller
{
    protected $boardCustomerService;

    public function __construct(BoardCustomerService $boardCustomerService)
    {
        $this->boardCustomerService = $boardCustomerService;
    }

    /**
     * @OA\Get(
     *     path="/api/board-customers",
     *     summary="Danh sách ban chấp hành",
     *     tags={"Board Customers"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Số lượng bản ghi trên mỗi trang",
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách ban chấp hành"
     *     )
     * )
     */

    public function index(Request $request)
    {
        $customers = $this->boardCustomerService->getBoardCustomers($request->search, $request->perPage, $request->status);
        return BoardCustomerResource::collection($customers);
    }

    /**
     * @OA\Post(
     *     path="/api/board-customers",
     *     summary="Thêm mới ban chấp hành",
     *     tags={"Board Customers"},
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/BoardCustomerRequest")),
     *     @OA\Response(response=201, description="Tạo thành công")
     * )
     */
    public function store(StoreBoardCustomerRequest $request)
    {

        $customer = $this->boardCustomerService->createCustomer($request->validated());
        return new BoardCustomerResource($customer);
    }

    /**
     * @OA\Get(
     *     path="/api/board-customers/{id}",
     *     summary="Chi tiết ban chấp hành",
     *     tags={"Board Customers"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Chi tiết ban chấp hành")
     * )
     */
    public function show($id)
    {
        $customer = $this->boardCustomerService->findCustomer($id);
        return new BoardCustomerResource($customer);
    }

    /**
     * @OA\Put(
     *     path="/api/board-customers/{id}",
     *     summary="Cập nhật ban chấp hành",
     *     tags={"Board Customers"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/BoardCustomerRequest")),
     *     @OA\Response(response=200, description="Cập nhật thành công")
     * )
     */
    public function update(UpdateBoardCustomerRequest $request, $id)
    {
        $customer = $this->boardCustomerService->updateCustomer($id, $request->validated());
        return new BoardCustomerResource($customer);
    }

    /**
     * @OA\Delete(
     *     path="/api/board-customers/{id}",
     *     summary="Xóa ban chấp hành",
     *     tags={"Board Customers"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Xóa thành công")
     * )
     */
    public function destroy($id)
    {
        $this->boardCustomerService->deleteCustomer($id);
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
