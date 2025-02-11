<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardCustomer;
use App\Models\BusinessCustomer;
use App\Models\IndividualCustomer;
use App\Models\BusinessPartner;
use App\Models\IndividualPartner;
use App\Models\Sponsorship;

class SponsorshipController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->search;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $sponsorships = Sponsorship::with('sponsorable')
            ->when($search, function ($query) use ($search) {
                return $query->where('product', 'LIKE', "%$search%");
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('sponsorship_date', [$startDate, $endDate]);
            })
            ->paginate(10);

        return view('sponsorships.index', compact('sponsorships'));
    }

    public function create()
    {
        // Lấy tất cả người tài trợ từ các bảng
        $boardCustomers = BoardCustomer::select('id', 'full_name', 'phone')
            ->whereNull('club_id')
            ->get()
            ->map(function ($customer) {
                $customer->type = BoardCustomer::class;
                return $customer;
            });

        $businessCustomers = BusinessCustomer::select('id', 'business_name_vi as full_name', 'phone')
            ->whereNull('club_id')
            ->get()
            ->map(function ($customer) {
                $customer->type = BusinessCustomer::class;
                return $customer;
            });

        $individualCustomers = IndividualCustomer::select('id', 'full_name', 'phone')
            ->whereNull('club_id')
            ->get()
            ->map(function ($customer) {
                $customer->type = IndividualCustomer::class;
                return $customer;
            });

        $businessPartners = BusinessPartner::select('id', 'business_name_vi as full_name', 'phone')
            ->whereNull('club_id')
            ->get()
            ->map(function ($customer) {
                $customer->type = BusinessPartner::class;
                return $customer;
            });

        $individualPartners = IndividualPartner::select('id', 'full_name', 'phone')
            ->whereNull('club_id')
            ->get()
            ->map(function ($customer) {
                $customer->type = IndividualPartner::class;
                return $customer;
            });

        $allCustomers = $boardCustomers
            ->concat($businessCustomers)
            ->concat($individualCustomers)
            ->concat($businessPartners)
            ->concat($individualPartners);
        return view('sponsorships.create', compact('allCustomers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sponsorable_id' => 'required|integer',
            'sponsorable_type' => 'required|string',
            'sponsorship_date' => 'required|date',
            'content' => 'nullable|string',
            'product' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', 
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $uniqueFileName = uniqid() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('attachments', $uniqueFileName, 'public');
        }

        $validatedData['attachment'] = $attachmentPath;

        Sponsorship::create($validatedData);

        return redirect()->route('sponsorship.index')->with('success', 'Tài trợ đã được thêm thành công.');
    }

    public function show($id)
    {
        $sponsorship = Sponsorship::with('sponsorable')->findOrFail($id);
        return view('sponsorships.show', compact('sponsorship'));
    }

    public function destroy($id)
    {
        $sponsorship = Sponsorship::findOrFail($id);
        $sponsorship->delete();

        return redirect()->route('sponsorship.index')->with('success', 'Tài trợ đã được xóa thành công.');
    }
}
