<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\DocumentService;
use App\Http\Resources\DocumentResource;
use App\Http\Requests\StoreDocumentRequest;
use App\Traits\ApiResponse;

/**
 * @OA\Tag(
 *     name="Documents",
 *     description="Quản lý tài liệu"
 * )
 */
class DocumentController extends Controller
{
    use ApiResponse;

    protected DocumentService $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * @OA\Get(
     *     path="/api/documents",
     *     summary="Lấy danh sách tài liệu",
     *     tags={"Documents"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Danh sách tài liệu", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Document")))
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $documents = $this->documentService->getDocuments($request);
        return $this->success(DocumentResource::collection($documents), 'Lấy danh sách tài liệu thành công.');
    }

    /**
     * @OA\Post(
     *     path="/api/documents",
     *     summary="Thêm mới tài liệu",
     *     tags={"Documents"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/StoreDocumentRequest")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tài liệu đã được thêm", @OA\JsonContent(ref="#/components/schemas/Document"))
     * )
     */
    public function store(StoreDocumentRequest $request): JsonResponse
    {
        $document = $this->documentService->storeDocument($request);
        return $this->success(new DocumentResource($document), 'Tài liệu đã được thêm thành công.', 201);
    }

     /**
     * @OA\Get(
     *     path="/api/documents/{id}/download",
     *     summary="Tải xuống tài liệu",
     *     tags={"Documents"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Tải xuống tệp", @OA\MediaType(mediaType="application/octet-stream", @OA\Schema(type="string", format="binary"))),
     *     @OA\Response(response=404, description="Tệp không tồn tại")
     * )
     */
    public function download(int $id)
    {
        $filePath = $this->documentService->downloadDocument($id);
        return $filePath ? response()->download($filePath) : $this->error('Tệp không tồn tại.', 404);
    }

    /**
     * @OA\Delete(
     *     path="/api/documents/{id}",
     *     summary="Xóa tài liệu",
     *     tags={"Documents"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Tài liệu đã được xóa")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->documentService->deleteDocument($id);
        return $this->success(null, 'Tài liệu đã được xóa thành công.');
    }
}
