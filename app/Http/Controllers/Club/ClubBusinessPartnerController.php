<?php

namespace App\Http\Controllers\Club;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessPartner;
use App\Models\Club;
use App\Models\Connector;

class ClubBusinessPartnerController extends Controller
{
    //
    public function index(Request $request, Club $club)
    {
        $search = $request->input('search');
        $partnerCategory = $request->input('partner_category');
        $groupId = $request->input('group_id'); 

        $businessPartners = $club->businessPartners() 
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
            ->when($groupId, function ($query, $groupId) {
                return $query->where('group_id', $groupId); 
            })
            ->paginate(10);

        return view('club.business_partner.index', [
            'club' => $club,
            'businessPartners' => $businessPartners,
            'search' => $search,
            'partnerCategory' => $partnerCategory,
        ]);
    }

    public function create(Club $club)
    {
        return view('club.business_partner.create', compact('club'));
    }

    public function store(Request $request, Club $club)
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

        $businessPartners = $club->businessPartners()->create($request->all());

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

        return redirect()->route('club.business_partner.index', $club->id)->with('success', 'Đối tác doanh nghiệp đã được thêm thành công!');
    }

    public function show(Club $club, $id)
    {
        $businessPartner = BusinessPartner::findOrFail($id);
        return view('club.business_partner.show', compact('businessPartner', 'club'));
    }

    public function edit(Club $club, $id)
    {
        $businessPartner = BusinessPartner::findOrFail($id);
        return view('club.business_partner.edit', compact('businessPartner', 'club'));
    }

    public function update(Request $request, Club $club, $id)
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

        return redirect()->route('club.business_partner.index', $club->id)->with('success', 'Thông tin đối tác doanh nghiệp đã được cập nhật thành công!');
    }

    public function destroy(Club $club, $id)
    {
        $businessPartner = BusinessPartner::findOrFail($id);
        $businessPartner->delete();

        return redirect()->route('club.business_partner.index', $club->id)->with('success', 'Đối tác doanh nghiệp đã được xóa thành công!');
    }

}
