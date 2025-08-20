<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses()->latest()->get();
        return view('addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('addresses.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'label' => 'nullable|string|max:100',
            'receiver_name' => 'required|string|max:150',
            'phone' => 'required|string|max:30',
            'address_line' => 'required|string',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'rajaongkir_city_id' => 'required|integer',
            'is_default' => 'nullable|boolean',
        ]);
        $data['is_default'] = $r->boolean('is_default');
        $addr = auth()->user()->addresses()->create($data);
        if ($addr->is_default) {
            auth()->user()->addresses()->where('id','!=',$addr->id)->update(['is_default'=>false]);
        }
        return redirect()->route('addresses.index')->with('ok','Saved');
    }

    public function edit(CustomerAddress $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        return view('addresses.edit', compact('address'));
    }

    public function update(Request $r, CustomerAddress $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        $data = $r->validate([
            'label' => 'nullable|string|max:100',
            'receiver_name' => 'required|string|max:150',
            'phone' => 'required|string|max:30',
            'address_line' => 'required|string',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'rajaongkir_city_id' => 'required|integer',
            'is_default' => 'nullable|boolean',
        ]);
        $data['is_default'] = $r->boolean('is_default');
        $address->update($data);
        if ($address->is_default) {
            auth()->user()->addresses()->where('id','!=',$address->id)->update(['is_default'=>false]);
        }
        return redirect()->route('addresses.index')->with('ok','Updated');
    }

    public function destroy(CustomerAddress $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        $address->delete();
        return back()->with('ok','Deleted');
    }
}
