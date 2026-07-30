<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\EventParticipant;
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

class AttendanceExport implements
    FromView,
    ShouldAutoSize,
    WithTitle,
    WithStyles,
    WithEvents
{
    protected Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Data untuk Blade
     */
    public function view(): View
    {
        $participants = EventParticipant::with([
                'checkin.user',
            ])
            ->where('event_id', $this->event->id)
            ->orderBy('participant_code')
            ->get();
        $totalParticipants = $participants->count();
        $totalPresent = $participants->whereNotNull('checkin')->count();
        $totalAbsent = $totalParticipants - $totalPresent;
        return view('checkin.excel', [
            'event' => $this->event,
            'participants' => $participants,
            'totalParticipants' => $totalParticipants,
            'totalPresent' => $totalPresent,
            'totalAbsent' => $totalAbsent,
            'exportedAt' => now(),
        ]);
    }

    /**
     * Nama Sheet
     */
    public function title(): string
    {
        return 'Absensi Peserta';
    }

    /**
     * Styling
     */
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

            10 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
    /**
     * Event Setelah Sheet Dibuat
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                /*
                 |---------------------------------------------------------
                 | Merge Header
                 |---------------------------------------------------------
                 */
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');
                /*
                 |---------------------------------------------------------
                 | Alignment
                 |---------------------------------------------------------
                 */
                $sheet->getStyle('A1:H2')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                /*
                 |---------------------------------------------------------
                 | Header Table
                 |---------------------------------------------------------
                 */
                $sheet->getStyle('A10:H10')->applyFromArray([
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
                /*
                 |---------------------------------------------------------
                 | Border Semua Data
                 |---------------------------------------------------------
                 */
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A10:H{$highestRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],

                        ],

                    ]);
                /*
                 |---------------------------------------------------------
                 | Freeze Header
                 |---------------------------------------------------------
                 */

                $sheet->freezePane('A11');
            },
        ];
    }
}
