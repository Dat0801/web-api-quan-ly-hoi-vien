<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $fileExtension = $request->input('file_extension');

        $documents = Document::query()
            ->when($search, function ($query, $search) {
                return $query->where('file_name', 'like', '%' . $search . '%')
                            ->orWhere('file_extension', 'like', '%' . $search . '%');
            })
            ->when($fileExtension, function ($query, $fileExtension) {
                return $query->where('file_extension', $fileExtension);
            })
            ->get();

        return response()->json([ 'success' => true, 'data' => $documents ]);
    }

    public function store(Request $request): JsonResponse
    {
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('documents', $fileName, 'public');

            $document = Document::create([
                'file_name' => $fileName,
                'file_extension' => $file->extension(),
                'file_path' => $path
            ]);

            return response()->json(['success' => true, 'data' => $document, 'message' => 'Tài liệu đã được thêm thành công'], 201);
        }

        return response()->json(['success' => false, 'message' => 'Vui lòng chọn tệp'], 400);
    }

    public function destroy($id): JsonResponse
    {
        $document = Document::findOrFail($id);
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();

        return response()->json(['success' => true, 'message' => 'Tài liệu đã được xóa thành công!']);
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);
        $filePath = 'public/' . $document->file_path;

        if (Storage::exists($filePath)) {
            return response()->download(storage_path('app/' . $filePath));
        }

        return response()->json(['success' => false, 'message' => 'Tệp không tồn tại.'], 404);
    }
}
