<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\GuestCategory;
use App\Models\Ceramonies;

class GuestCategoryLookupSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
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
        $names = Ceramonies::whereIn("id", $category->ceremony_ids ?? [])
            ->pluck("ceramony_name")
            ->implode(", ");

        $count = count($category->ceremony_ids ?? []);

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
}
