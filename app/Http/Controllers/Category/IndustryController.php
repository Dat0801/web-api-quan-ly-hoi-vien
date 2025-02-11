<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\IndustryRequest;
use App\Models\Industry;

class IndustryController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search'); 

        $industries = Industry::when($search, function ($query, $search) {
            return $query->where('industry_name', 'LIKE', '%' . $search . '%');
        })->paginate(perPage: 3); 

        return view('category.industry.index', compact('industries'));
    }
    public function create()
    {
        return view('category.industry.create'); 
    }

    public function store(IndustryRequest $request)
    {
        Industry::create($request->all());
        return redirect()->route('industry.index')->with('success', 'Thêm ngành thành công.');
    }

    public function show($id)
    {
        $industry = Industry::findOrFail($id);
        return view('category.industry.show', compact('industry'));
    }

    public function edit($id)
    {
        $industry = Industry::findOrFail($id);
        return view('category.industry.edit', compact('industry'));
    }

    public function update(IndustryRequest $request, $id)
    {
        $industry = Industry::findOrFail($id);
        $industry->update([
            'industry_code' => $request->industry_code,
            'industry_name' => $request->industry_name,
            'description' => $request->description,
        ]);

        return redirect()->route('industry.index')->with('success', 'Cập nhật ngành thành công!');
    }


    public function destroy(Industry $industry)
    {
        $industry->delete();
        return redirect()->route('industry.index')->with('success', 'Xóa ngành thành công.');
    }
}
