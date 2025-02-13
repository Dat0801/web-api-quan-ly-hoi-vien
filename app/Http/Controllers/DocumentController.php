<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\DocumentService;
use App\Http\Resources\DocumentResource;
use App\Http\Requests\StoreDocumentRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Traits\ApiResponse;

class DocumentController extends Controller
{
    use ApiResponse;

    protected DocumentService $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $documents = $this->documentService->getDocuments($request);
        return $this->success(DocumentResource::collection($documents), 'Lấy danh sách tài liệu thành công.');
    }

    public function store(StoreDocumentRequest $request): JsonResponse
    {
        $document = $this->documentService->storeDocument($request);
        return $this->success(new DocumentResource($document), 'Tài liệu đã được thêm thành công.', 201);
    }

    public function destroy($id): JsonResponse
    {
        $this->documentService->deleteDocument($id);
        return $this->success(null, 'Tài liệu đã được xóa thành công.');
    }

    public function download($id)
    {
        $filePath = $this->documentService->downloadDocument($id);

        return $filePath
            ? response()->download($filePath)
            : $this->error('Tệp không tồn tại.', 404);
    }
}
