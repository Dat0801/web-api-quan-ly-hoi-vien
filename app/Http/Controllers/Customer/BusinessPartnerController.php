<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MembershipFee;
use Illuminate\Http\Request;
use App\Models\BusinessPartner;
use App\Models\Connector;
use App\Models\Sponsorship;

class BusinessPartnerController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search');
        $partnerCategory = $request->input('partner_category');

        $businessPartners = BusinessPartner::whereNull('club_id') 
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('business_name_vi', 'like', "%{$search}%")
                        ->orWhere('business_name_en', 'like', "%{$search}%")
                        ->orWhere('login_code', 'like', "%{$search}%")
                        ->orWhere('card_code', 'like', "%{$search}%");
                });
            })
            ->when($partnerCategory, function ($query, $partnerCategory) {
                return $query->where('partner_category', $partnerCategory);
            })
            ->paginate(10);

        return view('customer.business_partner.index', [
            'businessPartners' => $businessPartners,
            'search' => $search,
            'partnerCategory' => $partnerCategory,
        ]);
    }

    public function create()
    {
        return view('customer.business_partner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'login_code' => 'required|unique:business_partners',
            'card_code' => 'required|unique:business_partners',
            'business_name_vi' => 'required',
            'business_name_en' => 'nullable',
            'business_name_abbr' => 'nullable',
            'partner_category' => 'required|in:Việt Nam,Quốc tế',
            'headquarters_address' => 'required',
            'branch_address' => 'nullable',
            'tax_code' => 'nullable',
            'phone' => 'required',
            'website' => 'nullable',
            'leader_name' => 'nullable',
            'leader_position' => 'nullable',
            'leader_phone' => 'nullable',
            'leader_gender' => 'nullable|in:male,female',
            'leader_email' => 'nullable|email',
            'club_id' => 'nullable|exists:clubs,id',
            'status' => 'nullable|boolean',
        ]);

        $businessPartners = BusinessPartner::create($request->all());

        if ($request->has('responsible_name') && count($request->responsible_name) > 0) {
            $connectors = [];
            foreach ($request->responsible_name as $index => $name) {
                if (!empty($name)) {
                    $connectors[] = [
                        'business_partner_id' => $businessPartners->id,
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

        return redirect()->route('business_partner.index')->with('success', 'Đối tác doanh nghiệp đã được thêm thành công!');
    }

    public function show($id)
    {
        $businessPartner = BusinessPartner::findOrFail($id);
        return view('customer.business_partner.show', compact('businessPartner'));
    }

    public function edit($id)
    {
        $businessPartner = BusinessPartner::findOrFail($id);
        return view('customer.business_partner.edit', compact('businessPartner'));
    }

    public function update(Request $request, $id)
    {
        $businessPartner = BusinessPartner::findOrFail($id);

        $request->validate([
            'login_code' => 'required|unique:business_partners,login_code,' . $id,
            'card_code' => 'required|unique:business_partners,card_code,' . $id,
            'business_name_vi' => 'required',
            'business_name_en' => 'nullable',
            'business_name_abbr' => 'nullable',
            'partner_category' => 'required|in:Việt Nam,Quốc tế',
            'headquarters_address' => 'required',
            'branch_address' => 'nullable',
            'tax_code' => 'nullable',
            'phone' => 'required',
            'website' => 'nullable',
            'leader_name' => 'nullable',
            'leader_position' => 'nullable',
            'leader_phone' => 'nullable',
            'leader_gender' => 'nullable|in:male,female',
            'leader_email' => 'nullable|email',
            'club_id' => 'nullable|exists:clubs,id',
            'status' => 'nullable|boolean',
        ]);

        $businessPartner->update($request->all());

        if ($request->has('responsible_name') && count($request->responsible_name) > 0) {
            $businessPartner->connector()->delete();

            $connectors = [];
            foreach ($request->responsible_name as $index => $name) {
                if (!empty($name)) {
                    $connectors[] = [
                        'business_partner_id' => $businessPartner->id,
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

        return redirect()->route('business_partner.index')->with('success', 'Thông tin đối tác doanh nghiệp đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $businessPartner = BusinessPartner::findOrFail($id);
        $businessPartner->delete();

        return redirect()->route('business_partner.index')->with('success', 'Đối tác doanh nghiệp đã được xóa thành công!');
    }

    public function sponsorshipHistory($customerId, Request $request)
    {
        $customer = BusinessPartner::findOrFail($customerId);

        $sponsorships = Sponsorship::where('sponsorable_id', $customerId)
            ->where('sponsorable_type', BusinessPartner::class)
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
        $customer = BusinessPartner::findOrFail($customerId);
        
        $query = MembershipFee::where('customer_id', $customerId)
            ->where('customer_type', BusinessPartner::class);

        $years = MembershipFee::where('customer_id', $customerId)
            ->where('customer_type', BusinessPartner::class)
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
