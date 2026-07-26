<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\ParticipantReceipt;
use Illuminate\Http\Request;
use App\Exports\ReceiptReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::orderBy('name')->get();

        $query = ParticipantReceipt::with([
            'participant.event',
            'receiptItems.item',
            'user'
        ]);

        // 1. Filter Event
        if ($request->filled('event_id')) {
            $query->whereHas('participant', function ($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        // 2. Filter Nama / Participant ID
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('participant', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('participant_code', 'like', "%{$keyword}%");
            });
        }

        $receipts = $query->latest('received_at')
                          ->paginate(10)
                          ->withQueryString(); // Ini memastikan filter tetap ada saat pindah halaman

        return view('reports.index', compact(
            'events',
            'receipts'
        ));
    }

    public function exportExcel(Request $request)
    {
        // Catatan Tambahan: Jika Anda ingin hasil Export Excel juga ikut terfilter
        // sesuai pencarian di layar, Anda perlu mengirim parameter filter ke dalam
        // class ReceiptReportExport() nantinya.
        return Excel::download(
            new ReceiptReportExport(),
            'laporan-penyerahan-souvenir.xlsx'
        );
    }

    public function exportPdf(Request $request)
        {
            $query = ParticipantReceipt::with([
                'participant.event',
                'receiptItems.item',
                'user'
            ]);

            // Filter Event
            if ($request->filled('event_id')) {
                $query->whereHas('participant', function ($q) use ($request) {
                    $q->where('event_id', $request->event_id);
                });
            }

            // Filter Keyword
            if ($request->filled('keyword')) {
                $keyword = $request->keyword;

                $query->whereHas('participant', function ($q) use ($keyword) {

                    $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('participant_code', 'like', "%{$keyword}%");

                });
            }

            $receipts = $query
                ->latest('received_at')
                ->get();

            $event = null;

            if ($request->filled('event_id')) {
                $event = Event::find($request->event_id);
            }

            $pdf = Pdf::loadView(
                'reports.pdf',
                compact(
                    'receipts',
                    'event'
                )
            );

            $pdf->setPaper('a4', 'landscape');

            return $pdf->download(
                'Laporan_Penyerahan_Souvenir_' .
                now()->format('Ymd_His') .
                '.pdf'
            );
        }
}
