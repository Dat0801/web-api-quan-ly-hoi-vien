<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MembershipFee;
use Illuminate\Http\Request;
use App\Models\BusinessCustomer;
use App\Models\Field;
use App\Models\Market;
use App\Models\TargetCustomerGroup;
use App\Models\Industry;
use App\Models\Certificate;
use App\Models\Club;
use App\Models\Connector;
use App\Models\Sponsorship;

class BusinessCustomerController extends Controller
{
    //
    public function index(Request $request)
    {
        $fields = Field::all();
        $markets = Market::all();
        $targetCustomerGroups = TargetCustomerGroup::all();

        $search = $request->input('search');
        $status = $request->input('status');
        $fieldId = $request->input('field_id');
        $marketId = $request->input('market_id');
        $targetCustomerGroupId = $request->input('target_customer_group_id');

        $customers = BusinessCustomer::when($search, function ($query, $search) {
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
            ->paginate(perPage: 10);

        return view('customer.business_customer.index', compact(
            'customers',
            'search',
            'status',
            'fields',
            'markets',
            'targetCustomerGroups'
        ));
    }

    public function create()
    {
        $industries = Industry::all();
        $fields = Field::all(); 
        $markets = Market::all();
        $targetCustomerGroups = TargetCustomerGroup::all(); 
        $certificates = Certificate::all(); 
        $clubs = Club::all(); 
        return view(
            'customer.business_customer.create',
            compact('industries', 'fields', 'markets', 'targetCustomerGroups', 'certificates', 'clubs')
        );
    }

    public function store(Request $request)
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
            'club_id' => 'nullable|exists:clubs,id',
            'responsible_name.*' => 'nullable|string',
            'responsible_position.*' => 'nullable|string',
            'responsible_phone.*' => 'nullable|string',
            'responsible_gender.*' => 'nullable|in:male,female',
            'responsible_email.*' => 'nullable|email',
        ]);

        $businessCustomer = BusinessCustomer::create([
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
            'club_id' => $request->club_id,
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

        return redirect()->route('business_customer.index')->with('success', 'Thêm khách hàng thành công!');
    }

    public function show($id)
    {
        $customer = BusinessCustomer::findOrFail($id);
        return view('customer.business_customer.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = BusinessCustomer::findOrFail($id);
        $industries = Industry::all(); // Lấy tất cả ngành
        $fields = Field::all(); // Lấy tất cả lĩnh vực
        $markets = Market::all(); // Lấy tất cả thị trường
        $targetCustomerGroups = TargetCustomerGroup::all(); // Lấy tất cả thị trường
        $certificates = Certificate::all(); // Lấy tất cả thị trường
        $clubs = Club::all(); // Lấy tất cả thị trường
        return view(
            'customer.business_customer.edit',
            compact('customer', 'industries', 'fields', 'markets', 'targetCustomerGroups', 'certificates', 'clubs')
        );
    }

    public function update(Request $request, $id)
    {
        $businessCustomer = BusinessCustomer::findOrFail($id);

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

        return redirect()->route('business_customer.index')->with('success', 'Cập nhật khách hàng thành công!');
    }

    public function destroy($id)
    {
        $customer = BusinessCustomer::findOrFail($id);
        $customer->delete();

        return redirect()->route('business_customer.index')->with('success', 'Xóa khách hàng thành công!');
    }

    public function sponsorshipHistory($customerId, Request $request)
    {
        $customer = BusinessCustomer::findOrFail($customerId);

        $sponsorships = Sponsorship::where('sponsorable_id', $customerId)
            ->where('sponsorable_type', BusinessCustomer::class)
            ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                return $query->whereBetween('sponsorship_date', [$request->start_date, $request->end_date]);
            })
            ->when($request->search, function ($query) use ($request) {
                return $query->where('product', 'LIKE', "%{$request->search}%");
            })
            ->get();
        $totalContribution = $sponsorships->sum('total_amount');
        return view('customer.sponsorship_history', compact('customer', 'sponsorships', 'totalContribution'));
    }

    public function membershipFeeHistory(Request $request, $customerId)
    {
        $customer = BusinessCustomer::findOrFail($customerId);
        
        $query = MembershipFee::where('customer_id', $customerId)
            ->where('customer_type', BusinessCustomer::class);

        $years = MembershipFee::where('customer_id', $customerId)
            ->where('customer_type', BusinessCustomer::class)
            ->select('year')
            ->distinct()
            ->pluck('year')
            ->sortDesc();

        if ($request->has('year') && $request->year != '') {
            $query->where('year', $request->year);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('content', 'like', '%' . $request->search . '%')
                    ->orWhere('notes', 'like', '%' . $request->search . '%');
            });
        }

        $fees = $query->orderBy('year', 'desc')->get();
        $totalFeesPaid = $fees->sum('amount_paid');

        return view('customer.membership_fee_history', compact('customer', 'fees', 'totalFeesPaid', 'years'));
    }
}
