<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Ceramonies;
use App\Models\GuestCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    { {
            $request->validate([
                'category_name' => 'required|string|max:255',
                'ceremony_ids' => 'required|array|min:1',
            ]);

            GuestCategory::create([
                'host_id' => Auth::id(),
                'category_name' => $request->category_name,
                'ceremony_ids' => $request->ceremony_ids, // Saved as JSON via Model casting
            ]);

            return redirect()->route('host.categories.index')->with('success', 'Category Created!');
        }
    }
}
