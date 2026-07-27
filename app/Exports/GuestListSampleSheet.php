<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use App\Models\GuestCategory;

class GuestListSampleSheet implements FromArray, WithHeadings, WithEvents, WithTitle
{
    protected $hostId;

    public function __construct($hostId)
    {
        $this->hostId = $hostId;
    }

    public function array(): array
    {
        // Category, group_type, and ceremonies are left empty (no dummy data)
        return [
            ["Rahul", "9876543210", "rahul@example.com", "groom", ""],
            ["Priya", "9123456789", "priya@example.com", "bride", ""],
        ];
    }

    public function headings(): array
    {
        return ["name", "number", "email", "relation", "category"];
    }

    public function title(): string
    {
        return "Guests";
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1. Enable Sheet Protection (Locks everything by default)
                $sheet->getProtection()->setSheet(true);
                // Adding a strict password ensures Microsoft Excel mathematically blocks any deletion or editing of locked cells
                $sheet->getProtection()->setPassword('wedding123');

                // 2. UNLOCK A2:E1000 (Name, Number, Email, Relation, AND Category)
                // Category (Col E) MUST be unlocked so users can pick from the dropdown!
                $sheet->getStyle('A2:E1000')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

                // 3. (Removed F and G locking since they are deleted)

                // 4. Dynamic Category Dropdown from DB
                $categories = GuestCategory::where("host_id", $this->hostId)->get();
                $categoriesCount = $categories->count();
                
                if ($categoriesCount > 0) {
                    $validation = $sheet->getCell("E2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(true);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle("Input error");
                    $validation->setError("Value is not in list.");
                    $validation->setPromptTitle("Pick from list");
                    $validation->setPrompt("Please pick a category from the drop-down list.");
                    
                    $categoryNames = $categories->pluck('category_name')->implode(',');
                    
                    // Google Sheets strips data validation pointing to hidden sheets.
                    // By providing the options as a string, it works perfectly. 
                    // Excel limits this string to 255 chars, so fallback to range if too long.
                    if (strlen($categoryNames) <= 250) {
                        $validation->setFormula1('"' . $categoryNames . '"');
                    } else {
                        $formula = "='Categories'!\$A\$2:\$A\$" . ($categoriesCount + 1);
                        $validation->setFormula1($formula);
                    }
                    

                    // Apply validation to Column E (Rows 2 to 1000)
                    for ($i = 2; $i <= 1000; $i++) {
                        $sheet->getCell("E{$i}")->setDataValidation(clone $validation);
                    }
                    // No extra columns to populate formulas for
                    // (Empty block)
                }
            }
        ];
    }
}