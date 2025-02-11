<?php

namespace App\Http\Controllers\Club;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\IndividualPartner;
use App\Models\Industry;
use App\Models\Field;

class ClubIndividualPartnerController extends Controller
{
    //
    public function index(Request $request, Club $club)
    {
        $search = $request->input('search');
        $partnerCategory = $request->input('partner_category');

        $partners = $club->individualPartners()
            ->when($search, function ($query, $search) {
                $query->where('full_name', 'like', "%{$search}%")
                    ->orWhere('login_code', 'like', "%{$search}%")
                    ->orWhere('card_code', 'like', "%{$search}%");
            })
            ->when($partnerCategory, function ($query, $partnerCategory) {
                return $query->where('partner_category', $partnerCategory);
            })
            ->paginate(10);

        return view('club.individual_partner.index', compact('club', 'partners', 'search', 'partnerCategory'));
    }

    public function create(Club $club)
    {
        $industries = Industry::all();
        $fields = Field::all();
        return view('club.individual_partner.create', compact('industries', 'fields', 'club'));
    }

    public function store(Request $request, Club $club)
    {
        $request->validate([
            'login_code' => 'required|unique:individual_partners',
            'card_code' => 'nullable|unique:individual_partners',
            'full_name' => 'required',
            'phone' => 'required',
            'partner_category' => 'required|in:Việt Nam,Quốc tế',
            'unit' => 'nullable',
            'position' => 'nullable',
            'industry_id' => 'nullable|exists:industries,id',
            'field_id' => 'nullable|exists:fields,id',
        ]);

        $request->merge(['club_id' => $club->id]);
        IndividualPartner::create($request->all());

        return redirect()->route('club.individual_partner.index', $club->id)->with('success', 'Thêm đối tác thành công!');
    }

    public function show(Club $club, $id)
    {
        $partner = IndividualPartner::findOrFail($id);
        return view('club.individual_partner.show', compact('partner', 'club'));
    }

    public function edit(Club $club, $id)
    {
        $industries = Industry::all();
        $fields = Field::all();
        $partner = IndividualPartner::findOrFail($id);
        return view('club.individual_partner.edit', compact('partner', 'industries', 'fields', 'club'));
    }

    public function update(Request $request, Club $club, $id)
    {
        $partner = IndividualPartner::findOrFail($id);

        $request->validate([
            'login_code' => 'required|unique:individual_partners,login_code,' . $id,
            'card_code' => 'required|unique:individual_partners,card_code,' . $id,
            'full_name' => 'required',
            'phone' => 'required',
            'partner_category' => 'required|in:Việt Nam,Quốc tế',
            'unit' => 'nullable',
            'position' => 'nullable',
            'industry_id' => 'nullable|exists:industries,id',
            'field_id' => 'nullable|exists:fields,id',
        ]);
        $request->merge(['club_id' => $club->id]);
        $partner->update($request->all());

        return redirect()->route('club.individual_partner.index', $club->id)->with('success', 'Cập nhật đối tác thành công!');
    }

    public function destroy(Club $club, $id)
    {
        $partner = IndividualPartner::findOrFail($id);
        $partner->delete();

        return redirect()->route('club.individual_partner.index', $club->id)->with('success', 'Xóa đối tác thành công!');
    }

}
