<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
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
        return [
            ["Rahul", "9876543210", "rahul@example.com", "friend", ""],
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
                $categoriesCount = GuestCategory::where("host_id", $this->hostId)->count();
                
                if ($categoriesCount > 0) {
                    $validation = $event->sheet->getCell("E2")->getDataValidation();
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
                    
                    // Reference the Categories sheet (e.g. Categories!$A$2:$A$10)
                    $formula = "=Categories!\$A\$2:\$A\$" . ($categoriesCount + 1);
                    $validation->setFormula1($formula);
                    
                    // Apply to column E (rows 2 to 1000)
                    for ($i = 2; $i <= 1000; $i++) {
                        $event->sheet->getCell("E{$i}")->setDataValidation(clone $validation);
                    }
                }
            }
        ];
    }
}
