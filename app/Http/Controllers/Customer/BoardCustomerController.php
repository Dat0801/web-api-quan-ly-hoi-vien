<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MembershipFee;
use Illuminate\Http\Request;
use App\Models\BoardCustomer;
use App\Models\Sponsorship;

class BoardCustomerController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $customers = BoardCustomer::whereNull('club_id')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('login_code', 'like', "%{$search}%")
                        ->orWhere('card_code', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                if ($status == 'active') {
                    return $query->where('status', 1);
                } elseif ($status == 'inactive') {
                    return $query->where('status', 0);
                }
            })
            ->paginate(10);

        return view('customer.board_customer.index', compact('customers', 'search', 'status'));
    }

    public function create()
    {
        return view('customer.board_customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'login_code' => 'required|unique:board_customers',
            'card_code' => 'required|unique:board_customers',
            'full_name' => 'required',
            'birth_date' => 'nullable|date',
            'gender' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'unit_name' => 'required',
            'unit_position' => 'required',
            'association_position' => 'required',
            'term' => 'required',
            'attendance_permission' => 'nullable|in:1,0',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = $file->getClientOriginalName();
            $uniqueFileName = uniqid() . '_' . $fileName;
            $avatarPath = $file->storeAs('avatars', $uniqueFileName, 'public');
        }

        $attendancePermission = $request->attendance_permission == '1' ? true : false;

        BoardCustomer::create([
            'login_code' => $request->login_code,
            'card_code' => $request->card_code,
            'full_name' => $request->full_name,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'email' => $request->email,
            'avatar' => $avatarPath,
            'unit_name' => $request->unit_name,
            'unit_position' => $request->unit_position,
            'association_position' => $request->association_position,
            'term' => $request->term,
            'attendance_permission' => $attendancePermission,
            'status' => true,
        ]);

        return redirect()->route('board_customer.index')->with('success', 'Thêm ban chấp hành thành công!');
    }

    public function show($id)
    {
        $customer = BoardCustomer::findOrFail($id);
        return view('customer.board_customer.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = BoardCustomer::findOrFail($id);
        return view('customer.board_customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = BoardCustomer::findOrFail($id);

        $request->validate([
            'full_name' => 'required',
            'birth_date' => 'nullable|date',
            'gender' => 'required',
            'phone' => 'required',
            'unit_name' => 'required',
            'unit_position' => 'required',
            'association_position' => 'required',
            'term' => 'required',
            'attendance_permission' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($customer->avatar) {
                $oldAvatarPath = public_path('storage/' . $customer->avatar);
                if (file_exists($oldAvatarPath)) {
                    unlink($oldAvatarPath);
                }
            }

            $file = $request->file('avatar');
            $fileName = $file->getClientOriginalName();
            $uniqueFileName = uniqid() . '_' . $fileName;
            $avatarPath = $file->storeAs('avatars', $uniqueFileName, 'public');
            $customer->avatar = $avatarPath;
        }

        $customer->update($request->except('avatar'));

        return redirect()->route('board_customer.index')->with('success', 'Cập nhật ban chấp hành thành công!');
    }

    public function destroy($id)
    {
        $customer = BoardCustomer::findOrFail($id);
        $customer->delete();

        return redirect()->route('board_customer.index')->with('success', 'Xóa ban chấp hành thành công!');
    }

    public function sponsorshipHistory($customerId, Request $request)
    {
        $customer = BoardCustomer::findOrFail($customerId);

        $sponsorships = Sponsorship::where('sponsorable_id', $customerId)
            ->where('sponsorable_type', BoardCustomer::class)
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
        $customer = BoardCustomer::findOrFail($customerId);

        $query = MembershipFee::where('customer_id', $customerId)
            ->where('customer_type', BoardCustomer::class);

        $years = MembershipFee::where('customer_id', $customerId)
            ->where('customer_type', BoardCustomer::class)
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
