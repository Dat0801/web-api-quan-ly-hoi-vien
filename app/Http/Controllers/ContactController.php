<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    //
    /**
     * Hiển thị thông tin liên hệ.
     */
    public function index()
    {
        $contact = Contact::firstOrFail(); // Lấy bản ghi đầu tiên
        return view('contact.index', compact('contact'));
    }

    /**
     * Hiển thị form chỉnh sửa thông tin liên hệ.
     */
    public function edit()
    {
        $contact = Contact::firstOrFail(); // Lấy bản ghi đầu tiên
        return view('contact.edit', compact('contact'));
    }

    /**
     * Cập nhật thông tin liên hệ.
     */
    public function update(Request $request)
    {
        $request->validate([
            'hotline' => 'required|string|max:25',
            'website' => 'nullable',
            'fanpage' => 'nullable',
            'email' => 'nullable',
            'address' => 'nullable|string|max:255',
        ]);

        $contact = Contact::firstOrFail(); // Lấy bản ghi đầu tiên
        $contact->update($request->all()); // Cập nhật dữ liệu

        return redirect()->route('contact.index')
                         ->with('success', 'Thông tin liên hệ đã được cập nhật thành công.');
    }
}
