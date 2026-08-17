<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryVenue;
use Illuminate\Http\Request;

class CategoryVenueController extends Controller
{
    public function index()
    {
        $categories = CategoryVenue::all();
        return view('admin.categoryvenue.index', compact('categories'));
    }

    public function create()
    {
        $categories = CategoryVenue::all(['id', 'category_name']);

        $allSubCategories = CategoryVenue::pluck('sub_categories')
            ->filter()
            ->flatten(1)
            ->pluck('name')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        return view('admin.categoryvenue.create', compact('categories', 'allSubCategories'));
    }

    public function getCategoryDetails($id)
    {
        $category = CategoryVenue::findOrFail($id);
        return response()->json($category);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name'                           => 'required|string',
            'sub_categories'                          => 'nullable|array',
            'sub_categories.*.name'                   => 'required|string',
            'sub_categories.*.ceremonies'             => 'nullable|array',
            'sub_categories.*.ceremonies.*'           => 'string',
            'sub_categories.*.html_files'             => 'nullable|array',
            'sub_categories.*.html_files.*'           => 'file|mimes:html|max:20480',
        ]);

        $category = CategoryVenue::firstOrNew(['category_name' => $request->category_name]);
        $existingSubCategories = is_array($category->sub_categories) ? $category->sub_categories : [];

        if ($request->sub_categories) {
            foreach ($request->sub_categories as $index => $incomingSub) {
                $subName = trim($incomingSub['name']);
                $incomingCeremonies = isset($incomingSub['ceremonies']) ? array_values(array_filter($incomingSub['ceremonies'])) : [];

                // Upload newly attached files for this specific subcategory
                $newFiles = [];
                if ($request->hasFile("sub_categories.{$index}.html_files")) {
                    foreach ($request->file("sub_categories.{$index}.html_files") as $file) {
                        $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('uploads/html_files'), $filename);
                        $newFiles[] = 'uploads/html_files/' . $filename;
                    }
                }

                $found = false;
                foreach ($existingSubCategories as &$existingSub) {
                    if (is_array($existingSub) && strcasecmp($existingSub['name'], $subName) === 0) {
                        // Merge ceremonies
                        $currentCeremonies = $existingSub['ceremonies'] ?? [];
                        $existingSub['ceremonies'] = array_values(array_unique(array_merge($currentCeremonies, $incomingCeremonies)));

                        // Merge HTML files
                        $currentFiles = $existingSub['html_files'] ?? [];
                        $existingSub['html_files'] = array_values(array_unique(array_merge($currentFiles, $newFiles)));

                        $found = true;
                        break;
                    }
                }
                unset($existingSub);

                if (!$found) {
                    $existingSubCategories[] = [
                        'name'        => $subName,
                        'ceremonies'  => $incomingCeremonies,
                        'html_files'  => $newFiles
                    ];
                }
            }
        }

        $category->sub_categories = $existingSubCategories;
        $category->save();

        return redirect()->route('admin.categoryvenue.index')->with('success', 'Category & subcategory templates saved successfully.');
    }

    public function edit($id)
    {
        $category = CategoryVenue::findOrFail($id);

        $allSubCategories = CategoryVenue::pluck('sub_categories')
            ->filter()
            ->flatten(1)
            ->pluck('name')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        return view('admin.categoryvenue.edit', compact('category', 'allSubCategories'));
    }

    public function update(Request $request, $id)
    {
        $category = CategoryVenue::findOrFail($id);

        $request->validate([
            'category_name'                => 'required|string|unique:category_venues,category_name,' . $id,
            'sub_categories'               => 'nullable|array',
            'sub_categories.*.name'        => 'required|string',
            'sub_categories.*.ceremonies'  => 'nullable|array',
            'sub_categories.*.ceremonies.*' => 'string',
            'sub_categories.*.html_files'  => 'nullable|array',
            'sub_categories.*.html_files.*' => 'file|mimes:html|max:20480',
        ]);

        // Collect all old files currently in DB
        $previousFiles = [];
        if (is_array($category->sub_categories)) {
            foreach ($category->sub_categories as $oldSub) {
                if (!empty($oldSub['html_files']) && is_array($oldSub['html_files'])) {
                    $previousFiles = array_merge($previousFiles, $oldSub['html_files']);
                }
            }
        }

        $subCategories = [];
        $retainedAllFiles = [];

        if ($request->sub_categories) {
            foreach ($request->sub_categories as $index => $sub) {
                if (!empty($sub['name'])) {
                    // Kept files submitted from the form
                    $retainedFiles = isset($sub['existing_files']) && is_array($sub['existing_files']) ? $sub['existing_files'] : [];

                    // Newly uploaded files
                    if ($request->hasFile("sub_categories.{$index}.html_files")) {
                        foreach ($request->file("sub_categories.{$index}.html_files") as $file) {
                            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                            $file->move(public_path('uploads/html_files'), $filename);
                            $retainedFiles[] = 'uploads/html_files/' . $filename;
                        }
                    }

                    $subCategories[] = [
                        'name'        => trim($sub['name']),
                        'ceremonies'  => isset($sub['ceremonies']) ? array_values(array_filter($sub['ceremonies'])) : [],
                        'html_files'  => array_values($retainedFiles)
                    ];

                    $retainedAllFiles = array_merge($retainedAllFiles, $retainedFiles);
                }
            }
        }

        // Delete removed files from disk
        $deletedFiles = array_diff($previousFiles, $retainedAllFiles);
        foreach ($deletedFiles as $deletedFile) {
            if (file_exists(public_path($deletedFile))) {
                @unlink(public_path($deletedFile));
            }
        }

        $category->update([
            'category_name'  => $request->category_name,
            'sub_categories' => $subCategories,
        ]);

        return redirect()->route('admin.categoryvenue.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = CategoryVenue::findOrFail($id);

        // Delete all HTML template files associated with each subcategory
        if (is_array($category->sub_categories)) {
            foreach ($category->sub_categories as $sub) {
                if (isset($sub['html_files']) && is_array($sub['html_files'])) {
                    foreach ($sub['html_files'] as $file) {
                        if (file_exists(public_path($file))) {
                            @unlink(public_path($file));
                        }
                    }
                }
            }
        }

        $category->delete();
        return redirect()->route('admin.categoryvenue.index')->with('success', 'Category deleted successfully.');
    }
}
