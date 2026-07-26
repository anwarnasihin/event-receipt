<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipantTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Participant Code',
            'Nama',
            'Email',
            'No HP',
            'Base Campus',
        ];
    }

    public function array(): array
    {
        return [
            [
                'D123456',
                'Budi Santoso',
                'budi@binus.edu',
                '08123456789',
                'Bekasi',
            ],
        ];
    }
}
