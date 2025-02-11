<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;

class BusinessController extends Controller
{
    //
    public function index(Request $request)
    {
        // Lấy giá trị từ trường tìm kiếm
        $search = $request->input('search');
        
        // Kiểm tra nếu có từ khóa tìm kiếm
        if ($search) {
            // Nếu có, tìm kiếm theo mã doanh nghiệp hoặc tên doanh nghiệp
            $businesses = Business::where('business_code', 'like', '%' . $search . '%')
                ->orWhere('business_name', 'like', '%' . $search . '%')
                ->paginate(3);
        } else {
            // Nếu không có từ khóa tìm kiếm, hiển thị tất cả các doanh nghiệp
            $businesses = Business::paginate(3);
        }

        return view('category.businesses.index', compact('businesses', 'search'));
    }

    public function create()
    {
        return view('category.businesses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_code' => 'required|unique:businesses|max:255',
            'business_name' => 'required|max:255',
        ]);

        Business::create($request->all());

        return redirect()->route('business.index')->with('success', 'Doanh nghiệp đã được thêm thành công.');
    }

    public function show(Business $business)
    {
        return view('category.businesses.show', compact('business'));
    }

    public function edit(Business $business)
    {
        return view('category.businesses.edit', compact('business'));
    }

    public function update(Request $request, Business $business)
    {
        $request->validate([
            'business_code' => 'required|max:255|unique:businesses,business_code,' . $business->id,
            'business_name' => 'required|max:255',
        ]);

        $business->update($request->all());

        return redirect()->route('business.index')->with('success', 'Doanh nghiệp đã được cập nhật thành công.');
    }

    public function destroy(Business $business)
    {
        $business->delete();
        return redirect()->route('business.index')->with('success', 'Doanh nghiệp đã được xóa.');
    }
}
