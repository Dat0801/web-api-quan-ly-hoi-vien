<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;

class CertificateController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->get('search');
        $certificates = Certificate::when($search, function ($query, $search) {
            $query->where('certificate_name', 'like', "%$search%");
        })->paginate(3);

        return view('category.certificate.index', compact('certificates'));
    }

    public function create()
    {
        return view('category.certificate.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'certificate_code' => 'required|unique:certificates,certificate_code|max:255',
            'certificate_name' => 'required|max:255',
        ]);

        Certificate::create($request->all());

        return redirect()->route('certificate.index')->with('success', 'Thêm chứng chỉ thành công.');
    }

    public function show(Certificate $certificate)
    {
        return view('category.certificate.show', compact('certificate'));
    }

    public function edit(Certificate $certificate)
    {
        return view('category.certificate.edit', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $request->validate([
            'certificate_code' => 'required|max:255|unique:certificates,certificate_code,' . $certificate->id,
            'certificate_name' => 'required|max:255',
        ]);

        $certificate->update($request->all());

        return redirect()->route('certificate.index')->with('success', 'Cập nhật chứng chỉ thành công.');
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('certificate.index')->with('success', 'Xóa chứng chỉ thành công.');
    }
}
