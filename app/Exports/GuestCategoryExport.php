<?php

namespace App\Exports;

use App\Models\GuestCategory;
use App\Models\Ceramonies;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GuestCategoryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $hostId;

    public function __construct($hostId)
    {
        $this->hostId = $hostId;
    }

    public function collection()
    {
        return GuestCategory::where('host_id', $this->hostId)->get();
    }

    public function map($category): array
    {
        $ceremonyIds = collect($category->ceremony_ids ?? [])->map(function($item) {
            return is_array($item) ? ($item['id'] ?? null) : $item;
        })->filter()->toArray();
        $names = Ceramonies::whereIn('id', $ceremonyIds)
            ->pluck('ceramony_name')
            ->implode(', ');

        return [
            $category->category_name,
            ucfirst($category->group_type),
            $names ?: 'No ceremonies selected'
        ];
    }

    public function headings(): array
    {
        return [
            'Category Name',
            'Group Type',
            'Included Ceremonies',
        ];
    }
}
