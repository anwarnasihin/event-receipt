<?php

namespace App\Exports;

use App\Models\ParticipantReceipt;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ReceiptReportExport implements FromView, ShouldAutoSize, WithTitle, WithStyles, WithEvents
{
    protected $eventId;
    protected $keyword;

    protected $totalParticipants = 0;
    protected $totalSouvenirType = 0;
    protected $totalItem = 0;
    protected $souvenirSummary = [];

    protected bool $showEventColumn = true;

    public function __construct($eventId = null, $keyword = null)
    {
        $this->eventId = $eventId;
        $this->keyword = $keyword;
    }

    public function view(): View
    {
        $query = ParticipantReceipt::with([
            'participant.event',
            'receiptItems.item',
            'user',
        ]);

        // Filter Event
        if ($this->eventId) {
            $query->whereHas('participant', function ($q) {
                $q->where('event_id', $this->eventId);
            });
        }

        // Filter Keyword
        if ($this->keyword) {
            $keyword = $this->keyword;
            $query->whereHas('participant', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('participant_code', 'like', "%{$keyword}%");
            });
        }

        $receipts = $query
            ->latest('received_at')
            ->get();

        $this->totalParticipants = $receipts->count();

        $this->totalSouvenirType = $receipts
            ->flatMap(function ($receipt) {
                return $receipt->receiptItems->pluck('item.name');
            })
            ->filter()
            ->unique()
            ->count();

        $this->totalItem = $receipts
            ->flatMap(function ($receipt) {
                return $receipt->receiptItems;
            })
            ->count();

        $this->souvenirSummary = $receipts
            ->flatMap(function ($receipt) {
                return $receipt->receiptItems;
            })
            ->groupBy(function ($item) {
                return optional($item->item)->name;
            })
            ->map(function ($items) {
                return $items->count();
            })
            ->sortKeys()
            ->toArray();

        $totalParticipants = $receipts->count();

        $totalItem = $receipts->sum(function ($receipt) {
            return $receipt->receiptItems->count();
        });

        $totalSouvenirType = $receipts
            ->flatMap(function ($receipt) {
                return $receipt->receiptItems->pluck('item.name');
            })
            ->unique()
            ->count();

        $souvenirSummary = $receipts
            ->flatMap(function ($receipt) {
                return $receipt->receiptItems;
            })
            ->groupBy(function ($item) {
                return optional($item->item)->name;
            })
            ->map(function ($items) {
                return $items->count();
            })
            ->sortKeys();

        $this->showEventColumn = $receipts
            ->pluck('participant.event.id')
            ->filter()
            ->unique()
            ->count() > 1;

        $event = null;
        if ($this->eventId) {
            $event = \App\Models\Event::find($this->eventId);
        }

        return view('reports.excel', [
            'receipts' => $receipts,
            'event' => $event,
            'exportedAt' => now(),
            'totalParticipants' => $totalParticipants,
            'totalItem' => $totalItem,
            'totalSouvenirType' => $totalSouvenirType,
            'souvenirSummary' => $souvenirSummary,
        ]);
    }

    public function title(): string
    {
        return 'Laporan Souvenir';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 18,
                ],
            ],
            2 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = $this->showEventColumn ? 'I' : 'H';

                $highestRow = $sheet->getHighestRow();
                $headerRow = null;

                for ($i = 1; $i <= $highestRow; $i++) {
                    if ($sheet->getCell("A{$i}")->getValue() == 'No') {
                        $headerRow = $i;
                        break;
                    }
                }

                if (!$headerRow) {
                    return;
                }

                // Agar baris DATA PESERTA menempel langsung di atas header tabel (mengisi baris kosong sebelumnya)
                $dataTitleRow = $headerRow - 1;
                if ($dataTitleRow < 7) {
                    $dataTitleRow = 7;
                }

                // Pindahkan header tabel utama naik 1 baris ke atas untuk menutup celah kosong
                $newHeaderRow = $dataTitleRow + 1;

                // Jika posisi header bergeser, kita pastikan isi tabel ikut naik (jika template view mencetak header di baris tertentu)
                // Di sini kita langsung format baris dataTitleRow sebagai "DATA PESERTA"
                $sheet->mergeCells("A{$dataTitleRow}:{$lastColumn}{$dataTitleRow}");

                $sheet->setCellValue("A{$dataTitleRow}", "DATA PESERTA");

                // Styling untuk teks DATA PESERTA (Bersih tanpa background biru, teks hitam di tengah)
                $sheet->getStyle("A{$dataTitleRow}:{$lastColumn}{$dataTitleRow}")
                ->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFFFFF',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getRowDimension($dataTitleRow)->setRowHeight(25);

                // Merge Judul Atas
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->mergeCells("A2:{$lastColumn}2");

                $sheet->mergeCells("C4:{$lastColumn}4");
                $sheet->mergeCells("C5:{$lastColumn}5");
                $sheet->mergeCells("C6:{$lastColumn}6");

                $sheet->getStyle('A4:B6')->getFont()->setBold(true);
                $sheet->getStyle('C4:D6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Center Judul
                $sheet->getStyle("A1:{$lastColumn}2")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Header Biru Tabel Utama
                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")
                    ->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => [
                                'rgb' => 'FFFFFF',
                            ],
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => '1F4E78',
                            ],
                        ],
                    ]);

                // Border Seluruh Data
                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$highestRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);

                // Kolom No Rata Tengah
                $sheet->getStyle("A" . ($headerRow + 1) . ":A{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setAutoFilter("A{$headerRow}:{$lastColumn}{$highestRow}");

                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getRowDimension($headerRow)->setRowHeight(22);
                $sheet->freezePane('A' . ($headerRow + 1));

                // SUMMARY DASHBOARD
                $summaryStart = $highestRow + 3;

                $sheet->mergeCells("A{$summaryStart}:B{$summaryStart}");
                $sheet->mergeCells("E{$summaryStart}:F{$summaryStart}");

                $sheet->setCellValue("A{$summaryStart}", "SUMMARY LAPORAN");
                $sheet->setCellValue("E{$summaryStart}", "RINGKASAN SOUVENIR");

                $sheet->setCellValue("A".($summaryStart+1), "Total Peserta");
                $sheet->setCellValue("B".($summaryStart+1), $this->totalParticipants);

                $sheet->setCellValue("A".($summaryStart+2), "Jenis Souvenir");
                $sheet->setCellValue("B".($summaryStart+2), $this->totalSouvenirType);

                $sheet->setCellValue("A".($summaryStart+3), "Total Item Dibagikan");
                $sheet->setCellValue("B".($summaryStart+3), $this->totalItem);

                $row = $summaryStart + 1;
                foreach ($this->souvenirSummary as $souvenir => $qty) {
                    $sheet->setCellValue("E{$row}", $souvenir);
                    $sheet->setCellValue("F{$row}", $qty);
                    $row++;
                }

                $lastSummaryRow = max(
                    $summaryStart + 3,
                    $row - 1
                );

                $sheet->getStyle("A{$summaryStart}:B{$lastSummaryRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);

                $sheet->getStyle("E{$summaryStart}:F{$lastSummaryRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);

                $sheet->getStyle("A{$summaryStart}:B{$summaryStart}")
                ->applyFromArray([
                    'font'=>[
                        'bold'=>true,
                        'color'=>['rgb'=>'FFFFFF']
                    ],
                    'fill'=>[
                        'fillType'=>Fill::FILL_SOLID,
                        'startColor'=>[
                            'rgb'=>'1F4E78'
                        ]
                    ],
                    'alignment'=>[
                        'horizontal'=>Alignment::HORIZONTAL_CENTER
                    ]
                ]);

                $sheet->getStyle("E{$summaryStart}:F{$summaryStart}")
                ->applyFromArray([
                    'font'=>[
                        'bold'=>true,
                        'color'=>['rgb'=>'FFFFFF']
                    ],
                    'fill'=>[
                        'fillType'=>Fill::FILL_SOLID,
                        'startColor'=>[
                            'rgb'=>'1F4E78'
                        ]
                    ],
                    'alignment'=>[
                        'horizontal'=>Alignment::HORIZONTAL_CENTER
                    ]
                ]);

                // Format Participant ID & No HP sebagai Text
                $highestColumn = $sheet->getHighestColumn();
                $participantIdColumn = null;
                $phoneColumn = null;

                for ($col = 'A'; $col <= $highestColumn; $col++) {
                    $header = $sheet->getCell($col.$headerRow)->getValue();

                    if ($header == 'Participant ID') {
                        $participantIdColumn = $col;
                    }

                    if ($header == 'No HP') {
                        $phoneColumn = $col;
                    }
                }

                for ($row = $headerRow + 1; $row <= $highestRow; $row++) {
                    if ($participantIdColumn) {
                        $value = $sheet->getCell($participantIdColumn.$row)->getValue();
                        $sheet->setCellValueExplicit(
                            $participantIdColumn.$row,
                            $value,
                            DataType::TYPE_STRING
                        );
                    }

                    if ($phoneColumn) {
                        $value = $sheet->getCell($phoneColumn.$row)->getValue();
                        $sheet->setCellValueExplicit(
                            $phoneColumn.$row,
                            $value,
                            DataType::TYPE_STRING
                        );
                    }
                }
            },
        ];
    }
}
