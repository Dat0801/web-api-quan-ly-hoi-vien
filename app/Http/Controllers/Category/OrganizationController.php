<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;

class OrganizationController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search');
        $organizations = Organization::query()
            ->when($search, function ($query, $search) {
                $query->where('organization_name', 'like', '%' . $search . '%')
                      ->orWhere('organization_code', 'like', '%' . $search . '%');
            })
            ->paginate(perPage: 3);

        return view('category.organization.index', compact('organizations', 'search'));
    }

    /**
     * Hiển thị form tạo mới tổ chức.
     */
    public function create()
    {
        return view('category.organization.create');
    }

    /**
     * Lưu tổ chức mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'organization_code' => 'required|unique:organizations|max:50',
            'organization_name' => 'required|max:100',
            'description' => 'nullable|max:500',
        ]);

        Organization::create($validatedData);

        return redirect()->route('organization.index')->with('success', 'Tổ chức đã được thêm thành công!');
    }

    /**
     * Hiển thị chi tiết tổ chức.
     */
    public function show(Organization $organization)
    {
        return view('category.organization.show', compact('organization'));
    }

    /**
     * Hiển thị form chỉnh sửa tổ chức.
     */
    public function edit(Organization $organization)
    {
        return view('category.organization.edit', compact('organization'));
    }

    /**
     * Cập nhật thông tin tổ chức trong cơ sở dữ liệu.
     */
    public function update(Request $request, Organization $organization)
    {
        $validatedData = $request->validate([
            'organization_code' => 'required|unique:organizations,organization_code,' . $organization->id . '|max:50',
            'organization_name' => 'required|max:100',
            'description' => 'nullable|max:500',
        ]);

        $organization->update($validatedData);

        return redirect()->route('organization.index')->with('success', 'Tổ chức đã được cập nhật thành công!');
    }

    /**
     * Xóa tổ chức khỏi cơ sở dữ liệu.
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();

        return redirect()->route('organization.index')->with('success', 'Tổ chức đã được xóa thành công!');
    }
}
