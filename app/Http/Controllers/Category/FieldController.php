<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Field;
use App\Models\Industry;
use App\Models\SubGroup;

class FieldController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search');
        $industryId = $request->input('industry_id');

        $industries = Industry::all();

        $fields = Field::when($search, function($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->when($industryId, function($query, $industryId) {
            return $query->where('industry_id', $industryId);
        })->paginate(3);

        return view('category.field.index', compact('fields', 'industries', 'search', 'industryId'));
    }

    public function create()
    {
        $industries = Industry::all(); 
        return view('category.field.create', compact('industries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:fields',
            'description' => 'nullable',
            'industry_id' => 'required',
            'sub_groups' => 'nullable|array',
            'sub_groups.*.name' => 'required|string', 
            'sub_groups.*.description' => 'nullable|string', 
        ]);

        $field = Field::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'industry_id' => $request->industry_id,
        ]);

        if ($request->has('sub_groups')) {
            foreach ($request->sub_groups as $sub_group_data) {
                SubGroup::create([
                    'name' => $sub_group_data['name'],
                    'description' => $sub_group_data['description'],
                    'field_id' => $field->id,
                ]);
            }
        }

        return redirect()->route('field.index', ['tab' => 'fields'])
                 ->with('success', 'Thêm lĩnh vực thành công!');
    }

    public function show($id)
    {
        $field = Field::with('subGroups')->findOrFail($id); 

        return view('category.field.show', compact('field'));
    }

    public function edit($id)
    {
        $field = Field::with('subGroups')->findOrFail($id); 
        $industries = Industry::all();
        return view('category.field.edit', compact('field', 'industries'));
    }

    public function update(Request $request, $id)
    {
        $field = Field::with('subGroups')->findOrFail($id);

        // Validate dữ liệu
        $request->validate([
            'name' => 'required',
            'code' => "required|unique:fields,code,{$id}",
            'description' => 'nullable',
            'industry_id' => 'required',
            'sub_groups' => 'nullable|array',
            'sub_groups.*.id' => 'nullable|exists:sub_groups,id',
            'sub_groups.*.name' => 'required|string',
            'sub_groups.*.description' => 'nullable|string',
        ]);

        // Cập nhật thông tin lĩnh vực
        $field->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'industry_id' => $request->industry_id,
        ]);

        $existingSubGroupIds = $field->subGroups->pluck('id')->toArray(); // Lấy danh sách ID nhóm con hiện có
        $submittedSubGroupIds = $request->input('sub_groups.*.id', []); // Lấy danh sách ID nhóm con từ yêu cầu

        // Cập nhật hoặc tạo nhóm con
        if ($request->has('sub_groups')) {
            foreach ($request->sub_groups as $subGroupData) {
                if (isset($subGroupData['id']) && in_array($subGroupData['id'], $existingSubGroupIds)) {
                    // Nếu nhóm con đã tồn tại, cập nhật
                    SubGroup::where('id', $subGroupData['id'])->update([
                        'name' => $subGroupData['name'],
                        'description' => $subGroupData['description'],
                    ]);
                } else {
                    // Nếu nhóm con chưa tồn tại, tạo mới
                    SubGroup::create([
                        'name' => $subGroupData['name'],
                        'description' => $subGroupData['description'],
                        'field_id' => $field->id,
                    ]);
                }
            }
        }

        // Xóa các nhóm con không được gửi trong yêu cầu (nếu cần)
        $subGroupsToDelete = array_diff($existingSubGroupIds, $submittedSubGroupIds);
        SubGroup::whereIn('id', $subGroupsToDelete)->delete();

        return redirect()->route('field.index', ['tab' => 'fields'])
            ->with('success', 'Cập nhật lĩnh vực thành công!');
    }


    public function destroySubGroup($id)
    {
        $subGroup = SubGroup::findOrFail($id);
        $subGroup->delete();
        return back();
    }

    public function destroy($id)
    {
        $field = Field::findOrFail($id);

        foreach ($field->subGroups as $subGroup) {
            $subGroup->delete(); 
        }

        $field->delete();

        return redirect()->route('field.index' , ['tab' => 'fields'])
        ->with('success', 'Lĩnh vực và các nhóm con đã được xóa thành công!');
    }
}
