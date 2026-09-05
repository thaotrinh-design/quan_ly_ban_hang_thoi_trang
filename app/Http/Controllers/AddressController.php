<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses;

        return view('profile.addresses', compact('addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'is_default' => 'nullable|boolean',
        ]);

        $user = auth()->user();

        if ($request->is_default) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'receiver_name' => $request->receiver_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_default' => $request->boolean('is_default'),
        ]);

        return back()->with('success', 'Thêm địa chỉ thành công.');
    }

    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->is_default) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        $address->update([
            'receiver_name' => $request->receiver_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_default' => $request->boolean('is_default'),
        ]);

        return back()->with('success', 'Cập nhật địa chỉ thành công.');
    }

    public function destroy(Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->delete();

        return back()->with('success', 'Xóa địa chỉ thành công.');
    }
}
