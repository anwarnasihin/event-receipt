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

        // Filter Event
        if ($request->filled('event_id')) {
            $query->whereHas('participant', function ($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        // Filter Nama / Participant ID
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->whereHas('participant', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('participant_code', 'like', "%{$keyword}%");
            });
        }

        $receipts = $query
            ->latest('received_at')
            ->paginate(10)
            ->withQueryString();

        return view('reports.index', compact(
            'events',
            'receipts'
        ));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new ReceiptReportExport(
                $request->event_id,
                $request->keyword
            ),
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

            // Summary
            $totalParticipants = $receipts->count();

            $totalItem = $receipts->sum(function ($receipt) {
                return $receipt->receiptItems->count();
            });

            $totalSouvenirType = $receipts->flatMap(function ($receipt) {return $receipt->receiptItems->pluck('item.name');
            })->unique()->count();

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

        $event = null;

        if ($request->filled('event_id')) {

            $event = Event::find($request->event_id);

        }

        $showEventColumn = !$request->filled('event_id');

        $pdf = Pdf::loadView(
            'reports.pdf',
            compact(
                'receipts',
                'event',
                'showEventColumn',
                'totalParticipants',
                'totalItem',
                'totalSouvenirType',
                'souvenirSummary',
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
