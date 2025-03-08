<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCertificateRequest;
use App\Http\Requests\UpdateCertificateRequest;
use App\Http\Resources\CertificateResource;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

/**
 * @OA\Tag(
 *     name="Certificate",
 *     description="Quản lý chứng chỉ"
 * )
 */
class CertificateController extends Controller
{
    use ApiResponse;

    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * @OA\Get(
     *     path="/api/certificates",
     *     summary="Lấy danh sách chứng chỉ",
     *     tags={"Certificates"},
     *     security={{"bearerAuth": {}}},    
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Tìm kiếm theo tên chứng chỉ",
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
     *         description="Số lượng chứng chỉ trên mỗi trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách chứng chỉ",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Certificate"))
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $certificate = $this->certificateService->getCertificate($request->search, $request->per_page);
        return $this->success(CertificateResource::collection($certificate), "Lấy danh sách chứng chỉ thành công");
    }

    /**
     * @OA\Post(
     *     path="/api/certificates",
     *     summary="Thêm chứng chỉ",
     *     tags={"Certificates"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreCertificateRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="chứng chỉ đã được tạo",
     *         @OA\JsonContent(ref="#/components/schemas/Certificate")
     *     )
     * )
     */
    public function store(StoreCertificateRequest $request): JsonResponse
    {
        $certificate = $this->certificateService->createCertificate($request->validated());
        return $this->success(new CertificateResource($certificate), 'chứng chỉ đã được tạo thành công!', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/certificates/{id}",
     *     summary="Lấy thông tin chứng chỉ",
     *     tags={"Certificates"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của chứng chỉ",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin chứng chỉ",
     *         @OA\JsonContent(ref="#/components/schemas/Certificate")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="chứng chỉ không tồn tại"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $certificate = $this->certificateService->getCertificateById($id);
        return $certificate ? $this->success(new CertificateResource($certificate)) : $this->error('chứng chỉ không tồn tại!', 404);
    }

    /**
     * @OA\Put(
     *     path="/api/certificates/{id}",
     *     summary="Cập nhật chứng chỉ",
     *     tags={"Certificates"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của chứng chỉ",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/StoreCertificateRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật chứng chỉ thành công",
     *         @OA\JsonContent(ref="#/components/schemas/Certificate")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="chứng chỉ không tồn tại"
     *     )
     * )
     */
    public function update(UpdateCertificateRequest $request, $id): JsonResponse
    {
        $certificate = $this->certificateService->updateCertificate($id, $request->validated());
        return $this->success(new CertificateResource($certificate), 'Cập nhật chứng chỉ thành công!');
    }

    /**
     * @OA\Delete(
     *     path="/api/certificates/{id}",
     *     summary="Xóa chứng chỉ",
     *     tags={"Certificates"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của chứng chỉ",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa chứng chỉ thành công"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="chứng chỉ không tồn tại hoặc không thể xóa"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        return $this->certificateService->deleteCertificate($id)
            ? $this->success(null, 'Xóa chứng chỉ thành công!')
            : $this->error('chứng chỉ không tồn tại hoặc không thể xóa!', 404);
    }
}
