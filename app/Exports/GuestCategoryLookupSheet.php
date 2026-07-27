<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\GuestCategory;
use App\Models\Ceramonies;

class GuestCategoryLookupSheet implements FromCollection, WithHeadings, WithTitle, WithMapping, WithEvents
{
    protected $hostId;

    public function __construct($hostId)
    {
        $this->hostId = $hostId;
    }

    public function collection()
    {
        return GuestCategory::where("host_id", $this->hostId)->get();
    }

    public function map($category): array
    {
        $ceremonyIds = collect($category->ceremony_ids ?? [])->map(function($item) {
            return is_array($item) ? ($item['id'] ?? null) : $item;
        })->filter()->toArray();
        $names = Ceramonies::whereIn("id", $ceremonyIds)
            ->pluck("ceramony_name")
            ->implode(", ");

        $count = count($ceremonyIds);

        return [
            $category->category_name,
            ucfirst($category->group_type),
            $count,
            $names ?: "None"
        ];
    }

    public function headings(): array
    {
        return [
            "Category Name (Use this exactly in Guests sheet)",
            "Group Type",
            "Ceremonies Count",
            "Included Ceremonies"
        ];
    }

    public function title(): string
    {
        return "Categories";
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Hide this sheet so the user only sees the Guests sheet
                $event->sheet->getDelegate()->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
            }
        ];
    }
}
