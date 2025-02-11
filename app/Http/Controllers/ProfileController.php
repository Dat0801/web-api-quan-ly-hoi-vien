<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    /*
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    */

    // Hiển thị thông tin người dùng
    public function show()
    {
        return view('profile.show', [
            'user' => Auth::user(),
        ]);
    }

    // Cập nhật thông tin người dùng
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'password' => 'nullable|string|min:6',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user->name = $request->input('name');
        $user->phone_number = $request->input('phone_number');

        if ($request->filled('password')) {
            $plainPassword = trim($request->input('password'));

            if (strlen($plainPassword) < 60 || !preg_match('/^\$2y\$/', $plainPassword)) {
                $user->password = bcrypt($plainPassword);
            } 
        }
       
        if ($request->hasFile('profile_image')) {
            if ($user->avatar) {
                $oldImagePath = public_path('storage/' . $user->avatar);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);  
                }
            }
            $image = $request->file('profile_image');
            $fileName = $image->getClientOriginalName();
            $uniqueFileName = uniqid() . '_' . $fileName;
            $imagePath = $image->storeAs('avatars', $uniqueFileName, 'public');
            $user->avatar = $imagePath; 

        }

        $user->save(); 

        return redirect()->route('profile.edit')->with('success', 'Thông tin đã được cập nhật.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
