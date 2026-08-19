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
            'category_name'                  => 'required|string',
            'sub_categories'                 => 'nullable|array',
            'sub_categories.*.name'          => 'required|string',
            'sub_categories.*.ceremonies'    => 'nullable|array',
            'sub_categories.*.ceremonies.*'  => 'string',
            'sub_categories.*.html_files'    => 'nullable|array',
            'sub_categories.*.html_files.*'  => 'file|mimes:html,htm|max:20480',
        ]);

        $category = CategoryVenue::firstOrNew(['category_name' => $request->category_name]);
        $existingSubCategories = is_array($category->sub_categories) ? $category->sub_categories : [];

        // Ensure upload directory exists
        $uploadDir = public_path('uploads/html_files');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if ($request->sub_categories) {
            foreach ($request->sub_categories as $index => $incomingSub) {
                $subName = trim($incomingSub['name']);
                $incomingCeremonies = isset($incomingSub['ceremonies']) ? array_values(array_filter($incomingSub['ceremonies'])) : [];

                // Upload, parse, and convert newly attached HTML files
                $newFiles = [];
                if ($request->hasFile("sub_categories.{$index}.html_files")) {
                    foreach ($request->file("sub_categories.{$index}.html_files") as $file) {
                        $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                        $targetPath = $uploadDir . '/' . $filename;

                        // Process HTML to inject placeholders automatically while preserving styling
                        $rawHtml = file_get_contents($file->getRealPath());
                        $processedHtml = $this->prepareHtmlTemplate($rawHtml);

                        file_put_contents($targetPath, $processedHtml);
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
            'category_name'                  => 'required|string|unique:category_venues,category_name,' . $id,
            'sub_categories'                 => 'nullable|array',
            'sub_categories.*.name'          => 'required|string',
            'sub_categories.*.ceremonies'    => 'nullable|array',
            'sub_categories.*.ceremonies.*'  => 'string',
            'sub_categories.*.html_files'    => 'nullable|array',
            'sub_categories.*.html_files.*'  => 'file|mimes:html,htm|max:20480',
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

        $uploadDir = public_path('uploads/html_files');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $subCategories = [];
        $retainedAllFiles = [];

        if ($request->sub_categories) {
            foreach ($request->sub_categories as $index => $sub) {
                if (!empty($sub['name'])) {
                    // Retained existing files
                    $retainedFiles = isset($sub['existing_files']) && is_array($sub['existing_files']) ? $sub['existing_files'] : [];

                    // Process and save newly uploaded files
                    if ($request->hasFile("sub_categories.{$index}.html_files")) {
                        foreach ($request->file("sub_categories.{$index}.html_files") as $file) {
                            $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                            $targetPath = $uploadDir . '/' . $filename;

                            $rawHtml = file_get_contents($file->getRealPath());
                            $processedHtml = $this->prepareHtmlTemplate($rawHtml);

                            file_put_contents($targetPath, $processedHtml);
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

        // Delete unreferenced files from disk
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

    /**
     * Auto-transforms arbitrary raw uploaded HTML by replacing dummy content with template placeholders.
     * Preserves internal CSS styling, structure, layout, and styling classes.
     */
    protected function prepareHtmlTemplate(string $htmlContent): string
    {
        // 1. Convert Couple Names (Handles "Groom & Bride", "Groom and Bride", "Groom weds Bride", "Groom ♥ Bride")
        $namePatterns = [
            '/(<[a-z0-9]+[^>]*>)\s*([A-Z][a-z]+(?:\s+[A-Z][a-z]+)?)\s*(?:&amp;|&|and|weds|♥|\+)\s*([A-Z][a-z]+(?:\s+[A-Z][a-z]+)?)\s*(<\/[a-z0-9]+>)/iu',
        ];
        $htmlContent = preg_replace(
            $namePatterns,
            '$1[GROOM_NAME] &amp; [BRIDE_NAME]$4',
            $htmlContent
        );

        // 2. Convert Dates (Handles "24 November 2026", "24th Nov, 2026", "2026-11-24", "11/24/2026")
        $datePattern = '/\b(?:\d{1,2}(?:st|nd|rd|th)?\s+(?:Jan(?:uary)?|Feb(?:ruary)?|Mar(?:ch)?|Apr(?:il)?|May|Jun(?:e)?|Jul(?:y)?|Aug(?:ust)?|Sep(?:tember)?|Oct(?:ober)?|Nov(?:ember)?|Dec(?:ember)?)[\s,]+\d{4}|\d{4}[-\/.]\d{1,2}[-\/.]\d{1,2}|\d{1,2}[-\/.]\d{1,2}[-\/.]\d{4})\b/iu';
        $htmlContent = preg_replace($datePattern, '[WEDDING_DATE]', $htmlContent);

        // 3. Convert Times (Handles "10:00 AM", "07:30 PM", "7 PM")
        $timePattern = '/\b\d{1,2}(?::\d{2})?\s*(?:AM|PM|am|pm)\b/';
        $htmlContent = preg_replace($timePattern, '[WEDDING_TIME]', $htmlContent);

        // 4. Auto-detect repeated card sections for ceremonies and wrap with dynamic loop tags
        if (!str_contains($htmlContent, '<!-- CEREMONY_ITEM -->')) {
            $ceremonyCardPattern = '/(<(?:div|li|article)[^>]*class=["\'][^"\']*(?:ceremony|event|program|timeline|function|schedule)[^"\']*["\'][^>]*>)(.*?)(<\/(?:div|li|article)>)/is';
            
            if (preg_match($ceremonyCardPattern, $htmlContent)) {
                $htmlContent = preg_replace_callback(
                    $ceremonyCardPattern,
                    function ($matches) {
                        $inner = $matches[2];
                        // Replace ceremony image source
                        $inner = preg_replace('/src=["\'][^"\']+["\']/', 'src="[CERAMONY_IMAGE]"', $inner);
                        // Replace heading with ceremony name
                        $inner = preg_replace('/(<h[1-6][^>]*>)(.*?)(<\/h[1-6]>)/is', '$1[CERAMONY_NAME]$3', $inner);
                        // Replace paragraph details with date & time
                        $inner = preg_replace('/(<p[^>]*>)(.*?)(<\/p>)/is', '$1[CERAMONY_DATE] at [CERAMONY_TIME]$3', $inner);

                        return '<!-- CEREMONY_ITEM -->' . $matches[1] . $inner . $matches[3] . '<!-- /CEREMONY_ITEM -->';
                    },
                    $htmlContent,
                    1 // Apply loop wrapping to the first matching card pattern
                );
            }
        }

        // 5. Fallback placeholder for ceremonies container if no repeating card was detected
        if (!str_contains($htmlContent, '[CEREMONIES]') && !str_contains($htmlContent, '<!-- CEREMONY_ITEM -->')) {
            if (str_contains($htmlContent, '</body>')) {
                $htmlContent = str_replace('</body>', '<div class="auto-ceremonies-wrapper">[CEREMONIES]</div></body>', $htmlContent);
            } else {
                $htmlContent .= '<div class="auto-ceremonies-wrapper">[CEREMONIES]</div>';
            }
        }

        return $htmlContent;
    }
}