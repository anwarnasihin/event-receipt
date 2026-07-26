<?php

namespace App\Exports;

use App\Models\ParticipantReceipt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReceiptReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ParticipantReceipt::with([
            'participant',
            'receiptItems.item',
            'user',
        ])->get()->map(function ($receipt) {

            return [

                'Nama' => optional($receipt->participant)->name,

                'Participant ID' => optional($receipt->participant)->participant_code,

                'No. HP' => optional($receipt->participant)->phone,

                'Base Campus' => optional($receipt->participant)->campus,

                'Souvenir' => $receipt->receiptItems
                    ->pluck('item.name')
                    ->implode(', '),

                'Tanggal' => optional($receipt->received_at)
                    ? \Carbon\Carbon::parse($receipt->received_at)->format('d-m-Y H:i')
                    : '',

                'Petugas' => optional($receipt->user)->name,

            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Participant ID',
            'No. HP',
            'Base Campus',
            'Souvenir',
            'Tanggal',
            'Petugas',
        ];
    }
}
