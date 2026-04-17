<?php

namespace App\Imports;

use App\Models\GuestList;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // <--- MUST HAVE THIS

class GuestListImport implements ToModel, WithHeadingRow
{
    private $host_id;

    public function __construct($host_id)
    {
        $this->host_id = $host_id;
    }

    public function model(array $row)
    {
        $number = $row['number'] ?? $row['guest_number'] ?? null;

        if (!$number) return null;

        // CHECK: Does THIS specific host already have this number?
        $alreadyExists = GuestList::where('host_id', $this->host_id)
            ->where('guest_number', $number)
            ->exists();

        if ($alreadyExists) {
            return null; // Skip this row so the import doesn't crash
        }

        return new GuestList([
            'host_id'      => $this->host_id,
            'guest_name'   => $row['name'] ?? 'Unknown',
            'guest_number' => $number,
            'guest_email'  => $row['email'] ?? null,
            'relation'     => $row['relation'] ?? null,
        ]);
    }
}
