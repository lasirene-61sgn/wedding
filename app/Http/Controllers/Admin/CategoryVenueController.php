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

        // 4. Advanced DOM Parsing for structural pattern detection
        $dom = new \DOMDocument();
        // Suppress errors due to invalid HTML often found in templates
        libxml_use_internal_errors(true);
        // Load with proper encoding and prevent adding extra html/body tags
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        // --- Helper to wrap node with comments ---
        $wrapWithComments = function($node, $itemName) use ($dom) {
            $startComment = $dom->createComment(" {$itemName} ");
            $endComment = $dom->createComment(" /{$itemName} ");
            $node->parentNode->insertBefore($startComment, $node);
            
            if ($node->nextSibling) {
                $node->parentNode->insertBefore($endComment, $node->nextSibling);
            } else {
                $node->parentNode->appendChild($endComment);
            }
        };

        // --- DETECT CEREMONIES ---
        // Look for repeating blocks (same tag) under the same parent that contain an img, a heading, and a paragraph
        $processedCeremonies = false;
        $containers = $xpath->query('//*[count(*[self::div or self::article or self::li or self::section]) > 1]');
        foreach ($containers as $container) {
            if ($processedCeremonies) break;
            
            $children = $xpath->query('./*[self::div or self::article or self::li or self::section]', $container);
            $validChildren = [];
            
            foreach ($children as $child) {
                $hasImg = $xpath->evaluate('count(.//img) > 0', $child);
                $hasHeading = $xpath->evaluate('count(.//h1 | .//h2 | .//h3 | .//h4 | .//h5 | .//h6) > 0', $child);
                $hasP = $xpath->evaluate('count(.//p | .//span) > 0', $child);
                
                if ($hasImg && $hasHeading && $hasP) {
                    $validChildren[] = $child;
                }
            }

            // If we found a repeating pattern of at least 2 cards matching the structure
            if (count($validChildren) >= 2) {
                $first = $validChildren[0];
                
                // Inject placeholders in the first child
                $img = $xpath->query('.//img', $first)->item(0);
                if ($img) $img->setAttribute('src', '[CERAMONY_IMAGE]');
                
                $headings = $xpath->query('.//h1 | .//h2 | .//h3 | .//h4 | .//h5 | .//h6', $first);
                if ($headings->length > 0) $headings->item(0)->nodeValue = '[CERAMONY_NAME]';
                
                $paras = $xpath->query('.//p', $first);
                if ($paras->length > 0) {
                    $paras->item(0)->nodeValue = '[CERAMONY_DATE] at [CERAMONY_TIME] - [VENUE_NAME]';
                }

                // Remove the other dummy cards to prevent duplicates
                for ($i = 1; $i < count($validChildren); $i++) {
                    $validChildren[$i]->parentNode->removeChild($validChildren[$i]);
                }

                $wrapWithComments($first, 'CEREMONY_ITEM');
                $processedCeremonies = true;
            }
        }

        // --- DETECT GALLERY ---
        // Look for repeating images (or wrappers with just an image)
        $processedGallery = false;
        foreach ($containers as $container) {
            if ($processedGallery) break;
            
            $children = $xpath->query('./*[self::div or self::li or self::figure or self::a or self::img]', $container);
            $validChildren = [];
            
            foreach ($children as $child) {
                // It should either BE an image, or contain exactly one image and no text/headings
                $isImg = $child->nodeName === 'img';
                $hasImg = $xpath->evaluate('count(.//img) = 1', $child);
                $hasHeading = $xpath->evaluate('count(.//h1 | .//h2 | .//h3 | .//h4 | .//h5 | .//h6) > 0', $child);
                
                if (($isImg || $hasImg) && !$hasHeading && trim($child->textContent) === '') {
                    $validChildren[] = $child;
                }
            }

            if (count($validChildren) >= 3) { // At least 3 dummy images to be considered a gallery
                $first = $validChildren[0];
                
                $img = $first->nodeName === 'img' ? $first : $xpath->query('.//img', $first)->item(0);
                if ($img) $img->setAttribute('src', '[GALLERY_IMAGE]');
                
                for ($i = 1; $i < count($validChildren); $i++) {
                    $validChildren[$i]->parentNode->removeChild($validChildren[$i]);
                }

                $wrapWithComments($first, 'GALLERY_ITEM');
                $processedGallery = true;
            }
        }

        // --- DETECT ALBUMS ---
        // Similar to gallery, but has a heading (title)
        $processedAlbums = false;
        foreach ($containers as $container) {
            if ($processedAlbums) continue;
            
            $children = $xpath->query('./*[self::div or self::li or self::figure or self::article]', $container);
            $validChildren = [];
            
            foreach ($children as $child) {
                $hasImg = $xpath->evaluate('count(.//img) = 1', $child);
                $hasHeading = $xpath->evaluate('count(.//h1 | .//h2 | .//h3 | .//h4 | .//h5 | .//h6 | .//strong | .//b) > 0', $child);
                $hasP = $xpath->evaluate('count(.//p) > 0', $child); // Albums usually don't have long descriptions in this UI
                
                if ($hasImg && $hasHeading && !$hasP) {
                    $validChildren[] = $child;
                }
            }

            // Make sure it doesn't overlap with ceremonies
            $first = count($validChildren) > 0 ? $validChildren[0] : null;
            if ($first && $first->previousSibling && $first->previousSibling->nodeName === '#comment' && strpos($first->previousSibling->nodeValue, 'CEREMONY_ITEM') !== false) {
                continue;
            }

            if (count($validChildren) >= 2) { 
                $first = $validChildren[0];
                
                $img = $xpath->query('.//img', $first)->item(0);
                if ($img) $img->setAttribute('src', '[ALBUM_IMAGE]');
                
                $headings = $xpath->query('.//h1 | .//h2 | .//h3 | .//h4 | .//h5 | .//h6 | .//strong | .//b', $first);
                if ($headings->length > 0) $headings->item(0)->nodeValue = '[ALBUM_NAME]';
                
                for ($i = 1; $i < count($validChildren); $i++) {
                    $validChildren[$i]->parentNode->removeChild($validChildren[$i]);
                }

                $wrapWithComments($first, 'ALBUM_ITEM');
                $processedAlbums = true;
            }
        }

        $htmlContent = $dom->saveHTML();
        // Remove the xml encoding declaration added for unicode safety
        $htmlContent = str_replace('<?xml encoding="UTF-8">', '', $htmlContent);
        
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