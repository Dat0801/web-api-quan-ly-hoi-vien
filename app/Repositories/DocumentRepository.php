<?php

namespace App\Repositories;

use App\Models\Document;
use App\Interfaces\DocumentRepositoryInterface;

class DocumentRepository extends BaseRepository implements DocumentRepositoryInterface
{
    public function __construct(Document $model)
    {
        parent::__construct($model);
    }
    
    public function getDocuments(?string $search, ?string $fileExtension)
    {
        return Document::query()
            ->when($search, fn($query) => $query->where('file_name', 'like', "%$search%"))
            ->when($fileExtension, fn($query) => $query->where('file_extension', $fileExtension))
            ->paginate(10);
    }
}
