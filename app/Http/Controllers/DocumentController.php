<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\DocumentService;
use App\Http\Resources\DocumentResource;
use App\Http\Requests\StoreDocumentRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $documents = $this->documentService->getDocuments($request);
        return DocumentResource::collection($documents);
    }

    public function store(StoreDocumentRequest $request): JsonResponse
    {
        $document = $this->documentService->storeDocument($request);
        return response()->json([
            'success' => true,
            'data'    => new DocumentResource($document),
            'message' => 'Tài liệu đã được thêm thành công'
        ], 201);
    }

    public function destroy($id): JsonResponse
    {
        $this->documentService->deleteDocument($id);
        return response()->json([
            'success' => true,
            'message' => 'Tài liệu đã được xóa thành công!'
        ]);
    }

    public function download($id)
    {
        $filePath = $this->documentService->downloadDocument($id);

        return $filePath
            ? response()->download($filePath)
            : response()->json(['success' => false, 'message' => 'Tệp không tồn tại.'], 404);
    }
}
