<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\DocumentRepositoryInterface;

class DocumentService
{
    protected $documentRepo;

    public function __construct(DocumentRepositoryInterface $documentRepo)
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

        return $this->documentRepo->create([
            'file_name' => $fileName,
            'file_extension' => $file->extension(),
            'file_path' => $path
        ]);
    }

    public function deleteDocument($id)
    {
        $document = $this->documentRepo->findById($id);
        if (!$document) {
            return false;
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        return $this->documentRepo->delete($id);
    }

    public function downloadDocument($id)
    {
        $document = $this->documentRepo->findById($id);
        if (!$document) {
            return null;
        }

        $filePath = 'public/' . $document->file_path;
        return Storage::exists($filePath) ? storage_path('app/' . $filePath) : null;
    }
}
