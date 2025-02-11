<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MembershipTier;

class MembershipTierController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = MembershipTier::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $tiers = $query->paginate(10);

        return view('membership_tier.index', compact('tiers'));
    }

    public function show($id)
    {
        $membershipTier = MembershipTier::findOrFail($id);

        return view('membership_tier.show', compact('membershipTier'));
    }

    public function edit($id)
    {
        $membershipTier = MembershipTier::findOrFail($id);

        return view('membership_tier.edit', compact('membershipTier'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fee' => 'required|numeric|min:0',
            'minimum_contribution' => 'required|numeric|min:0',
        ]);

        $membershipTier = MembershipTier::findOrFail($id);

        $membershipTier->update([
            'name' => $request->name,
            'description' => $request->description,
            'fee' => $request->fee,
            'minimum_contribution' => $request->minimum_contribution,
        ]);

        return redirect()->route('membership_tier.index')->with('success', 'Cập nhật hạng thành viên thành công!');
    }

    public function destroy($id)
    {
        MembershipTier::destroy($id);
        return redirect()->route('membership_tier.index');
    }
}
