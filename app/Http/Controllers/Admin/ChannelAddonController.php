<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChannelAddon;
use Illuminate\Http\Request;

class ChannelAddonController extends Controller
{
    public function index()
    {
        $addons = ChannelAddon::latest()->get();
        return view('admin.addons.index', compact('addons'));
    }

    public function create()
    {
        return view('admin.addons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'type'      => 'required|in:whatsapp,sms,email',
            'count'     => 'required|integer|min:1',
            'price'     => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        ChannelAddon::create([
            'name'      => $request->name,
            'type'      => $request->type,
            'count'     => $request->count,
            'price'     => $request->price,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on created successfully.');
    }

    public function edit($id)
    {
        $addon = ChannelAddon::findOrFail($id);
        return view('admin.addons.edit', compact('addon'));
    }

    public function update(Request $request, $id)
    {
        $addon = ChannelAddon::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:100',
            'type'      => 'required|in:whatsapp,sms,email',
            'count'     => 'required|integer|min:1',
            'price'     => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $addon->update([
            'name'      => $request->name,
            'type'      => $request->type,
            'count'     => $request->count,
            'price'     => $request->price,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on updated successfully.');
    }

    public function destroy($id)
    {
        ChannelAddon::findOrFail($id)->delete();
        return redirect()->route('admin.addons.index')->with('success', 'Add-on deleted.');
    }
}
