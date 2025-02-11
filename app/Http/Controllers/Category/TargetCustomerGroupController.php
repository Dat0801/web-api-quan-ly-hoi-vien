<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TargetCustomerGroup;

class TargetCustomerGroupController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search');
        $groups = TargetCustomerGroup::when($search, function ($query, $search) {
            return $query->where('group_name', 'LIKE', '%' . $search . '%');
        })->paginate(perPage: 3);
        return view('category.target_customer_group.index', compact('groups'));
    }

    public function create()
    {
        return view('category.target_customer_group.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_code' => 'required|unique:target_customer_groups,group_code',
            'group_name' => 'required',
        ]);

        TargetCustomerGroup::create($request->all());
        return redirect()->route('target_customer_group.index')->with('success', 'Thêm nhóm khách hàng mục tiêu thành công.');
    }

    public function show($id)
    {
        $group = TargetCustomerGroup::findOrFail($id);
        return view('category.target_customer_group.show', compact('group'));
    }

    public function edit($id)
    {
        $group = TargetCustomerGroup::findOrFail($id);
        return view('category.target_customer_group.edit', compact('group'));
    }

    public function update(Request $request, $id)
    {
        $group = TargetCustomerGroup::findOrFail($id);
        $request->validate([
            'group_code' => 'required|unique:target_customer_groups,group_code,' . $id,
            'group_name' => 'required',
        ]);

        $group->update($request->all());
        return redirect()->route('target_customer_group.index')->with('success', 'Cập nhật nhóm khách hàng mục tiêu thành công.');
    }

    public function destroy($id)
    {
        $group = TargetCustomerGroup::findOrFail($id);
        $group->delete();
        return redirect()->route('target_customer_group.index')->with('success', 'Xóa nhóm khách hàng mục tiêu thành công.');
    }
}
