<?php

namespace App\Services;

use App\Repositories\DocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    protected $documentRepo;

    public function __construct(DocumentRepository $documentRepo)
    {
        $this->documentRepo = $documentRepo;
    }

    public function getDocuments(Request $request)
    {
        $search = $request->input('search');
        $fileExtension = $request->input('file_extension');

        return $this->documentRepo->getAllDocuments($search, $fileExtension);
    }

    public function storeDocument(Request $request)
    {
        $file = $request->file('document');
        $fileName = $file->getClientOriginalName();
        $path = $file->storeAs('documents', $fileName, 'public');

        return $this->documentRepo->createDocument([
            'file_name' => $fileName,
            'file_extension' => $file->extension(),
            'file_path' => $path
        ]);
    }

    public function deleteDocument($id)
    {
        $document = $this->documentRepo->findDocumentById($id);
        if (!$document) {
            return false;
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        return $this->documentRepo->deleteDocument($id);
    }

    public function downloadDocument($id)
    {
        $document = $this->documentRepo->findDocumentById($id);
        if (!$document) {
            return null;
        }

        $filePath = 'public/' . $document->file_path;
        return Storage::exists($filePath) ? storage_path('app/' . $filePath) : null;
    }
}
