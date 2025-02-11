<?php

namespace App\Http\Controllers\Club;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\BoardCustomer;

class ClubBoardCustomerController extends Controller
{
    //
    public function index(Club $club)
    {
        $boardCustomers = $club->boardCustomers()
            ->when(request('status'), function ($query) {
                return $query->where('status', request('status') == 'active' ? 1 : 0);
            })
            ->when(request('search'), function ($query) {
                return $query->where('full_name', 'like', '%' . request('search') . '%');
            })
            ->paginate(10);

        return view('club.board_customer.index', compact('club', 'boardCustomers'));
    }

    public function create(Club $club)
    {
        return view('club.board_customer.create', compact('club'));
    }

    public function store(Request $request, Club $club)
    {
        $validatedData = $request->validate([
            'login_code' => 'required|string|max:255|unique:board_customers,login_code',
            'full_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:15',
            'unit_name' => 'nullable|string|max:255',
            'unit_position' => 'nullable|string|max:255',
            'association_position' => 'nullable|string|max:255',
            'term' => 'nullable|string|max:255',
        ]);

        $club->boardCustomers()->create([
            'login_code' => $validatedData['login_code'],
            'full_name' => $validatedData['full_name'],
            'birth_date' => $validatedData['birth_date'] ?? null,
            'gender' => $validatedData['gender'] ?? null,
            'phone' => $validatedData['phone'] ?? null,
            'email' => $validatedData['email'] ?? null,
            'unit_name' => $validatedData['unit_name'] ?? null,
            'unit_position' => $validatedData['unit_position'] ?? null,
            'association_position' => $validatedData['association_position'] ?? null,
            'term' => $validatedData['term'] ?? null,
            'club_id' => $club->id,
        ]);

        return redirect()
            ->route('club.board_customer.index', $club->id)
            ->with('success', 'Ban điều hành đã được thêm thành công.');
    }

    public function show(Club $club, $id)
    {
        $boardCustomer = BoardCustomer::findOrFail($id);
        return view('club.board_customer.show', compact('club', 'boardCustomer'));
    }

    public function edit(Club $club, BoardCustomer $boardCustomer)
    {
        return view('club.board_customer.edit', compact('club', 'boardCustomer'));
    }

    public function update(Request $request, Club $club, BoardCustomer $boardCustomer)
    {
        $validated = $request->validate([
            'login_code' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:15',
            'unit_name' => 'nullable|string|max:255',
            'unit_position' => 'nullable|string|max:255',
            'association_position' => 'nullable|string|max:255',
            'term' => 'nullable|string|max:255',
        ]);

        $boardCustomer->update($validated);

        return redirect()->route('club.board_customer.index', $club->id)
            ->with('success', 'Thông tin ban điều hành đã được cập nhật thành công.');
    }

    public function destroy(Club $club, BoardCustomer $boardCustomer)
    {
        $boardCustomer->delete();

        return redirect()->route('club.board_customer.index', $club->id)
            ->with('success', 'Ban điều hành đã được xóa thành công.');
    }
}
