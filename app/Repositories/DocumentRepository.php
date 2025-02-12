<?php

namespace App\Repositories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Collection;

class DocumentRepository
{
    public function getAllDocuments(?string $search, ?string $fileExtension): Collection
    {
        return Document::query()
            ->when($search, fn($query) => $query->where('file_name', 'like', "%$search%"))
            ->when($fileExtension, fn($query) => $query->where('file_extension', $fileExtension))
            ->get();
    }

    public function findDocumentById(int $id): ?Document
    {
        return Document::find($id);
    }

    public function createDocument(array $data): Document
    {
        return Document::create($data);
    }

    public function deleteDocument(int $id): bool
    {
        return Document::destroy($id);
    }
}
