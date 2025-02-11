<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    //
    public function index(Request $request)
    {
        $status = $request->get('status');
        $role_id = $request->get('role_id');
        $search = $request->get('search');

        $roles = Role::all();

        $accounts = User::query()
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($role_id, function ($query) use ($role_id) {
                return $query->where('role_id', $role_id);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone_number', 'like', '%' . $search . '%');
                });
            })
            ->with('role')
            ->paginate(10);

        return view('user.account.index', compact('accounts', 'roles'));
    }


    public function show($id)
    {
        $account = User::with('role')->findOrFail($id);

        return view('user.account.show', compact('account'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('user.account.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:15',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:1,0',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = $file->getClientOriginalName();
            $uniqueFileName = uniqid() . '_' . $fileName;
            $avatarPath = $file->storeAs('avatars', $uniqueFileName, 'public');
            $validatedData['avatar'] = $avatarPath;
        }

        User::create($validatedData);

        return redirect()->route('account.index')->with('success', 'Tài khoản đã được tạo thành công.');
    }

    public function edit($id)
    {
        $account = User::findOrFail($id);
        $roles = Role::all();
        return view('user.account.edit', compact('account', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $account = User::findOrFail($id);

        $validatedData = $request->validate([
            'phone_number' => 'nullable|string|max:15',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'password' => 'nullable|string|min:8|confirmed', 
        ]);

        if ($request->hasFile('avatar')) {
            if ($account->avatar) {
                $oldAvatarPath = public_path('storage/' . $account->avatar);
                if (file_exists($oldAvatarPath)) {
                    unlink($oldAvatarPath);
                }
            }

            $file = $request->file('avatar');
            $fileName = $file->getClientOriginalName();
            $uniqueFileName = uniqid() . '_' . $fileName;
            $avatarPath = $file->storeAs('avatars', $uniqueFileName, 'public');
            $validatedData['avatar'] = $avatarPath;
        }

        if ($request->filled('password')) {
            $validatedData['password'] = bcrypt($request->password);
        } else {
            unset($validatedData['password']);
        }

        $account->update($validatedData);

        return redirect()->route('account.index')->with('success', 'Tài khoản đã được cập nhật thành công.');
    }


    public function destroy($id)
    {
        $account = User::findOrFail($id);

        $account->delete();

        return redirect()->route('account.index')->with('success', 'Tài khoản đã được xóa thành công.');
    }
}
