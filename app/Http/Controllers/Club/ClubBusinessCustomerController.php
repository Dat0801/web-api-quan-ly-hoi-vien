<?php

namespace App\Http\Controllers\Club;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Connector;
use App\Models\Industry;
use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\BusinessCustomer;
use App\Models\Field;
use App\Models\Market;
use App\Models\TargetCustomerGroup;


class ClubBusinessCustomerController extends Controller
{
    //
    public function index(Request $request, Club $club)
    {
        $fields = Field::all();
        $markets = Market::all();
        $targetCustomerGroups = TargetCustomerGroup::all();

        $search = $request->input('search');
        $status = $request->input('status');
        $fieldId = $request->input('field_id');
        $marketId = $request->input('market_id');
        $targetCustomerGroupId = $request->input('target_customer_group_id');
        $customers = $club->businessCustomers()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('business_name_vi', 'like', "%{$search}%")
                        ->orWhere('business_name_en', 'like', "%{$search}%")
                        ->orWhere('login_code', 'like', "%{$search}%")
                        ->orWhere('card_code', 'like', "%{$search}%")
                        ->orWhereHas('field', function ($fieldQuery) use ($search) {
                            $fieldQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('market', function ($marketQuery) use ($search) {
                            $marketQuery->where('market_name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('targetCustomerGroup', function ($groupQuery) use ($search) {
                            $groupQuery->where('group_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, function ($query, $status) {
                if ($status === 'active') {
                    return $query->where('status', 1);
                } elseif ($status === 'inactive') {
                    return $query->where('status', 0);
                }
            })
            ->when($fieldId, function ($query, $fieldId) {
                return $query->where('field_id', $fieldId);
            })
            ->when($marketId, function ($query, $marketId) {
                return $query->where('market_id', $marketId);
            })
            ->when($targetCustomerGroupId, function ($query, $targetCustomerGroupId) {
                return $query->where('target_customer_group_id', $targetCustomerGroupId);
            })
            ->paginate(10);
        return view('club.business_customer.index', compact(
            'club',
            'customers',
            'search',
            'status',
            'fields',
            'markets',
            'targetCustomerGroups'
        ));
    }

    public function create(Club $club)
    {
        $industries = Industry::all();
        $fields = Field::all();
        $markets = Market::all();
        $targetCustomerGroups = TargetCustomerGroup::all();
        $certificates = Certificate::all();

        return view(
            'club.business_customer.create',
            compact('industries', 'fields', 'markets', 'targetCustomerGroups', 'certificates', 'club')
        );
    }


    public function store(Request $request, Club $club)
    {
        $request->validate([
            'login_code' => 'required|unique:business_customers',
            'card_code' => 'required|unique:business_customers',
            'business_name_vi' => 'required',
            'business_name_en' => 'nullable',
            'business_name_abbr' => 'nullable',
            'headquarters_address' => 'required',
            'branch_address' => 'nullable',
            'tax_code' => 'nullable',
            'phone' => 'required',
            'website' => 'nullable',
            'fanpage' => 'nullable',
            'established_date' => 'nullable|date',
            'charter_capital' => 'nullable|numeric',
            'pre_membership_revenue' => 'nullable|numeric',
            'email' => 'required|email',
            'leader_name' => 'required|string',
            'leader_position' => 'required|string',
            'leader_phone' => 'required|string',
            'leader_gender' => 'required|string',
            'leader_email' => 'required|email',
            'industry_id' => 'nullable|exists:industries,id',
            'field_id' => 'nullable|exists:fields,id',
            'market_id' => 'nullable|exists:markets,id',
            'target_customer_group_id' => 'nullable|exists:target_customer_groups,id',
            'certificate_id' => 'nullable|exists:certificates,id',
            'responsible_name.*' => 'nullable|string',
            'responsible_position.*' => 'nullable|string',
            'responsible_phone.*' => 'nullable|string',
            'responsible_gender.*' => 'nullable|in:male,female',
            'responsible_email.*' => 'nullable|email',
        ]);

        $businessCustomer = $club->businessCustomers()->create([
            'login_code' => $request->login_code,
            'card_code' => $request->card_code,
            'business_name_vi' => $request->business_name_vi,
            'business_name_en' => $request->business_name_en,
            'business_name_abbr' => $request->business_name_abbr,
            'headquarters_address' => $request->headquarters_address,
            'branch_address' => $request->branch_address,
            'tax_code' => $request->tax_code,
            'phone' => $request->phone,
            'website' => $request->website,
            'fanpage' => $request->fanpage,
            'established_date' => $request->established_date,
            'leader_name' => $request->leader_name,
            'leader_position' => $request->leader_position,
            'leader_phone' => $request->leader_phone,
            'leader_gender' => $request->leader_gender,
            'leader_email' => $request->leader_email,
            'charter_capital' => $request->charter_capital,
            'pre_membership_revenue' => $request->pre_membership_revenue,
            'email' => $request->email,
            'industry_id' => $request->industry_id,
            'field_id' => $request->field_id,
            'market_id' => $request->market_id,
            'target_customer_group_id' => $request->target_customer_group_id,
            'certificate_id' => $request->certificate_id,
        ]);

        if ($request->has('responsible_name') && count($request->responsible_name) > 0) {
            $connectors = [];
            foreach ($request->responsible_name as $index => $name) {
                if (!empty($name)) {
                    $connectors[] = [
                        'business_customer_id' => $businessCustomer->id,
                        'name' => $name,
                        'position' => $request->responsible_position[$index] ?? null,
                        'phone' => $request->responsible_phone[$index] ?? null,
                        'gender' => $request->responsible_gender[$index] ?? null,
                        'email' => $request->responsible_email[$index] ?? null,
                    ];
                }
            }

            if (!empty($connectors)) {
                Connector::insert($connectors);
            }
        }

        return redirect()->route('club.business_customer.index', $club->id)
            ->with('success', 'Thêm khách hàng thành công!');
    }

    public function show(Club $club, $id)
    {
        $customer = $club->businessCustomers()->findOrFail($id);
        return view('club.business_customer.show', compact('club', 'customer'));
    }

    public function edit(Club $club, $id)
    {
        $customer = $club->businessCustomers()->findOrFail($id);
        $industries = Industry::all();
        $fields = Field::all();
        $markets = Market::all();
        $targetCustomerGroups = TargetCustomerGroup::all();
        $certificates = Certificate::all();
        $clubs = Club::all();

        return view('club.business_customer.edit', compact('club', 'customer', 'industries', 'fields', 'markets', 'targetCustomerGroups', 'certificates', 'clubs'));
    }
    public function update(Request $request, Club $club, $id)
    {
        $businessCustomer = $club->businessCustomers()->findOrFail($id);

        $request->validate([
            'login_code' => 'required|unique:business_customers,login_code,' . $id, 
            'card_code' => 'required|unique:business_customers,card_code,' . $id,   
            'business_name_vi' => 'required',
            'business_name_en' => 'nullable',
            'business_name_abbr' => 'nullable',
            'headquarters_address' => 'required',
            'branch_address' => 'nullable',
            'tax_code' => 'nullable',
            'phone' => 'required',
            'website' => 'nullable',
            'fanpage' => 'nullable',
            'established_date' => 'nullable|date',
            'established_decision' => 'nullable',
            'leader_name' => 'nullable|string',
            'leader_position' => 'nullable|string',
            'leader_phone' => 'nullable|string',
            'leader_gender' => 'nullable|string',
            'leader_email' => 'nullable|email',
            'charter_capital' => 'nullable|numeric',
            'pre_membership_revenue' => 'nullable|numeric',
            'email' => 'nullable|email',
            'industry_id' => 'nullable|exists:industries,id',
            'field_id' => 'nullable|exists:fields,id',
            'market_id' => 'nullable|exists:markets,id',
            'target_customer_group_id' => 'nullable|exists:target_customer_groups,id',
            'certificate_id' => 'nullable|exists:certificates,id',
            'club_id' => 'nullable|exists:clubs,id',
        ]);

        $businessCustomer->update($request->all());

        if ($request->has('responsible_name') && count($request->responsible_name) > 0) {
            $businessCustomer->connector()->delete(); 

            $connectors = [];
            foreach ($request->responsible_name as $index => $name) {
                if (!empty($name)) {
                    $connectors[] = [
                        'business_customer_id' => $businessCustomer->id,
                        'name' => $name,
                        'position' => $request->responsible_position[$index] ?? null,
                        'phone' => $request->responsible_phone[$index] ?? null,
                        'gender' => $request->responsible_gender[$index] ?? null,
                        'email' => $request->responsible_email[$index] ?? null,
                    ];
                }
            }

            if (!empty($connectors)) {
                Connector::insert($connectors);
            }
        }

        return redirect()->route('club.business_customer.index', $club->id)->with('success', 'Cập nhật khách hàng thành công!');
    }

    public function destroy(Club $club, BusinessCustomer $businessCustomer)
    {
        $businessCustomer->delete();

        return redirect()->route('club.business_customer.index', $club->id)->with('success', 'Xóa khách hàng thành công!');
    }
}
