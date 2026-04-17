<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryVenue;
use Illuminate\Http\Request;

class CategoryVenueController extends Controller
{
    public function index(){
        $categories = CategoryVenue::all();
        return view('admin.categoryvenue.index', compact('categories'));
    }

    public function create(){
        return view('admin.categoryvenue.create');
    }

    public function store(Request $request){
        $validated =$request->validate([
            'category_name' => 'required|unique:category_venues,category_name'
        ]);
        $validated['category_name'] = $request->category_name;
        CategoryVenue::create($validated);
        return redirect()->route('admin.categoryvenue.index')->with('success', 'category venue created');
    }

    public function edit($id){
        $category = CategoryVenue::findOrFail($id);
        return view('admin.categoryvenue.edit', compact('category'));
    }

    public function update(Request $request, $id){
        $category = CategoryVenue::findOrFail($id);
        $validated = $request->validate([
            'category_name' => 'nullable|unique:category_venues,category_name,' . $id,
        ]);
        
        $category->update($validated);
        return redirect()->route('admin.categoryvenue.index')->with('success', 'caetgory venue updated');
    }

    public function destroy($id){
        $category = CategoryVenue::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.categoryvenue.index')->with('success', 'category deleted');
    }
}
