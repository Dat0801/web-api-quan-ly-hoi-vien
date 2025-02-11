<?php

namespace App\Http\Controllers\Club;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\Industry;
use App\Models\Field;
use App\Models\IndividualCustomer;

class ClubIndividualCustomerController extends Controller
{
    //
    public function index(Request $request, Club $club)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $customers = $club->individualCustomers()
            ->when($search, function ($query, $search) {
                return $query->where('full_name', 'like', "%{$search}%")
                    ->orWhere('login_code', 'like', "%{$search}%")
                    ->orWhere('card_code', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                if ($status == 'active') {
                    return $query->where('status', 1);
                } elseif ($status == 'inactive') {
                    return $query->where('status', 0);
                }
            })
            ->paginate(10);

        return view('club.individual_customer.index', compact('club', 'customers', 'search', 'status'));
    }

    public function create(Club $club)
    {
        $industries = Industry::all();
        $fields = Field::all();
        return view('club.individual_customer.create', compact('industries', 'fields', 'club'));
    }

    public function store(Request $request, Club $club)
    {
        $request->validate([
            'login_code' => 'required|unique:individual_customers',
            'card_code' => 'nullable|unique:individual_customers',
            'full_name' => 'required',
            'birth_date' => 'nullable|date',
            'position' => 'nullable',
            'gender' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:individual_customers',
            'unit' => 'nullable',
            'industry_id' => 'nullable|exists:industries,id',
            'field_id' => 'nullable|exists:fields,id',
        ]);

        $request->merge(['club_id' => $club->id]);
        $club->individualCustomers()->create($request->all());

        return redirect()->route('club.individual_customer.index', $club->id)->with('success', 'Thêm khách hàng thành công!');
    }

    public function show(Club $club, $id)
    {
        $customer = $club->individualCustomers()->findOrFail($id);
        return view('club.individual_customer.show', compact('customer', 'club'));
    }

    public function edit(Club $club, $id)
    {
        $industries = Industry::all(); 
        $fields = Field::all(); 
        $customer = $club->individualCustomers()->findOrFail($id);
        return view('club.individual_customer.edit', compact('customer', 'industries', 'fields', 'club'));
    }

    public function update(Request $request, Club $club, $id)
    {
        $customer = $club->individualCustomers()->findOrFail($id);

        $request->validate([
            'login_code' => 'required|unique:individual_customers,login_code,' . $id,
            'card_code' => 'required|unique:individual_customers,card_code,' . $id,
            'full_name' => 'required',
            'birth_date' => 'nullable|date',
            'gender' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:individual_customers,email,' . $id,
            'unit' => 'nullable',
            'position' => 'nullable',
            'industry_id' => 'nullable|exists:industries,id',
            'field_id' => 'nullable|exists:fields,id',
        ]);
        
        $request->merge(['club_id' => $club->id]);
        $customer->update($request->all());

        return redirect()->route('club.individual_customer.index', $club->id)->with('success', 'Cập nhật khách hàng thành công!');
    }

    public function destroy(Club $club, $id)
    {
        $customer = IndividualCustomer::findOrFail($id);
        $customer->delete();

        return redirect()->route('club.individual_customer.index', $club->id)->with('success', 'Xóa khách hàng thành công!');
    }

}
