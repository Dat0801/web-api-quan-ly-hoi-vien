<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MembershipFee;
use App\Models\BoardCustomer;
use App\Models\BusinessCustomer;
use App\Models\IndividualCustomer;
use App\Models\BusinessPartner;
use App\Models\IndividualPartner;

class MembershipFeeController extends Controller
{
    //
    public function index(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $status = $request->input('status');
        $search = $request->input('search');

        $query = MembershipFee::with('customer');

        if ($start_date && $end_date) {
            $query->whereBetween('payment_date', [$start_date, $end_date]);
        }

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $currentYear = date('Y');

        $fees = $query->where('year', $currentYear)
            ->orderBy('year', 'desc')
            ->paginate(6);

        return view('membership_fee.index', compact('fees'));
    }

    public function create()
    {
        $memberships = MembershipFee::select('customer_id', 'customer_type')
            ->distinct()
            ->get();

        return view('membership_fee.create', compact('memberships'));
    }

    public function getCustomerDebts($customerId, Request $request)
    {
        $customerType = $request->query('type');

        if (!$customerType) {
            return response()->json(['error' => 'Customer type is required'], 400);
        }

        $debts = MembershipFee::where('customer_id', $customerId)
            ->where('customer_type', $customerType)
            ->where('remaining_amount', '>', 0)
            ->select('year', 'amount_due')
            ->get();

        return response()->json([
            'debts' => $debts,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required',
            'fee_date' => 'required|date',
            'content' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,txt|max:10240',
            'years_count' => 'nullable|integer|min:1',
            'debt' => 'nullable|array',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $customer = MembershipFee::where('customer_id', $request->member_id)
            ->where('customer_type', $request->member_type)
            ->first()
            ->customer;

        $customerType = get_class($customer);
        
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();
            $uniqueFileName = uniqid() . '_' . $fileName;
            $attachmentPath = $file->storeAs('attachments', $uniqueFileName, 'public');
        }

        $debtYears = $request->input('debt', []);
        
        foreach ($debtYears as $year) {
            $membershipFee = MembershipFee::firstOrNew([
                'customer_id' => $customer->id,
                'customer_type' => $customerType,
                'year' => $year
            ]);

            $remainingAmount = ($membershipFee->amount_due ?? 0) - $membershipFee->amount_due;
            $membershipFee->fill([
                'amount_paid' => $membershipFee->amount_due,
                'remaining_amount' => $remainingAmount,
                'status' => $remainingAmount <= 0 ? 1 : 0,
                'content' => $request->content,
                'attachment' => $attachmentPath,
                'payment_date' => $request->fee_date,
            ])->save();
        }

        if ($request->years_count) {
            $membershipFee = MembershipFee::firstOrNew([
                'customer_id' => $customer->id,
                'customer_type' => $customerType,
            ]);
            $currentYear = date('Y');
            $yearsToPay = $request->years_count;
            $amountPerYear = $membershipFee->amount_due;

            for ($i = 1; $i <= $yearsToPay; $i++) {
                $year = $currentYear + $i;
                $membershipFee = MembershipFee::firstOrNew([
                    'customer_id' => $customer->id,
                    'customer_type' => $customerType,
                    'year' => $year
                ]);

                $remainingAmount = 1500000 - $amountPerYear;
                $membershipFee->fill([
                    'amount_due' => 1500000,
                    'amount_paid' => $amountPerYear,
                    'remaining_amount' => $remainingAmount,
                    'status' => $remainingAmount <= 0 ? 1 : 0,
                    'content' => $request->content,
                    'attachment' => $attachmentPath,
                    'payment_date' => $request->fee_date,
                ])->save();
            }
        }

        return redirect()->route('membership_fee.index')->with('success', 'Hội phí đã được thêm hoặc cập nhật thành công.');
    }

    public function show($id)
    {
        $fee = MembershipFee::with('customer')
            ->where('id', $id)
            ->firstOrFail();
        $fees = MembershipFee::where('customer_id', $fee->customer_id)
            ->where('customer_type', $fee->customer_type)
            ->orderBy('year', 'desc')
            ->get();
        return view('membership_fee.show', compact('fees', 'fee'));
    }


}
