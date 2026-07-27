<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Ceramonies;
use App\Models\GuestCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\GuestCategoryExport;
use Maatwebsite\Excel\Facades\Excel;

class GuestCategoryController extends Controller
{
    public function index()
    {
        // Fetch all categories created by this host
        $categories = GuestCategory::where('host_id', Auth::id())->get();

        // Fetch all ceremonies so the view/modal has access to them
        $ceramonies = Ceramonies::where('host_id', Auth::id())->get();

        // Pass BOTH variables to the view
        return view('host.categories.index', compact('categories', 'ceramonies'));
    }

    public function create()
    {
        $ceramonies = Ceramonies::where('host_id', Auth::id())->get();
        return view('host.categories.create', compact('ceramonies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'ceremony_ids' => 'required|array|min:1',
            'group_types' => 'required|array',
        ]);

        $ceremoniesData = [];
        foreach ($request->ceremony_ids as $id) {
            $ceremoniesData[] = [
                'id' => $id,
                'group_type' => $request->group_types[$id] ?? 'single',
            ];
        }

        GuestCategory::create([
            'host_id' => Auth::id(),
            'category_name' => $request->category_name,
            'ceremony_ids' => $ceremoniesData, // Saved as JSON via Model casting
            'group_type' => 'mixed', // Legacy column
        ]);

        return redirect()->route('host.categories.index')->with('success', 'Category Created!');
    }

    public function exportExcel()
    {
        return Excel::download(new GuestCategoryExport(Auth::id()), 'guest_categories.xlsx');
    }
}
