<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\CeramonyBackground;
use App\Models\HostFamilyDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostFamilyController extends Controller
{
    public function index()
    {
        $hfamily = HostFamilyDetails::where('host_id', Auth::id())->get();
        return view('host.hfamily.index', compact('hfamily'));
    }

    public function create()
    {
        $backgrounds = CeramonyBackground::all();
        return view('host.hfamily.create', compact('backgrounds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'textone' => 'nullable',
            'texttwo' => 'nullable',
            'textthree' => 'nullable',
            'textfour' => 'nullable',
            'textfive' => 'nullable',
            'textsix' => 'nullable',
            'textseven' => 'nullable',
            'topic_title_one' => 'nullable',
            'topic_title_two' => 'nullable',
            'topic_title_three' => 'nullable',
            'topic_title_four' => 'nullable',
            'topic_title_five' => 'nullable',
            'topic_tilte_six' => 'nullable',
            'selected_background_id' => 'nullable',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['host_id'] = Auth::id();

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        HostFamilyDetails::create($validated);
        return redirect()->route('host.hfamily.index')->with('success', 'Host Family Created Successfully');
    }

    public function edit(HostFamilyDetails $hfamily)
    {
        if($hfamily->host_id != Auth::id() ){
            abort(403);
        }
        $backgrounds = CeramonyBackground::all();
        return view('host.hfamily.edit', compact('backgrounds', 'hfamily'));
    }

    public function update(Request $request, HostFamilyDetails $hfamily)
    {
        if($hfamily->host_id != Auth::id()){
            abort(403);
        }
        $validated = $request->validate([
            'textone' => 'nullable',
            'texttwo' => 'nullable',
            'textthree' => 'nullable',
            'textfour' => 'nullable',
            'textfive' => 'nullable',
            'textsix' => 'nullable',
            'textseven' => 'nullable',
            'topic_title_one' => 'nullable',
            'topic_title_two' => 'nullable',
            'topic_title_three' => 'nullable',
            'topic_title_four' => 'nullable',
            'topic_title_five' => 'nullable',
            'topic_tilte_six' => 'nullable',
            'selected_background_id' => 'nullable',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $hfamily->update($validated);

        return redirect()->route('host.hfamily.index')->with('Success', 'Family Details Updated Successfully');
    }
}
