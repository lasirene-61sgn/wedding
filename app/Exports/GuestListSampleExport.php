<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Auth;

class GuestListSampleExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $hostId = Auth::id();
        
        return [
            new GuestListSampleSheet($hostId),
            new GuestCategoryLookupSheet($hostId)
        ];
    }
}
