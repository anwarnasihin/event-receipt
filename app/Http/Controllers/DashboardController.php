<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventItem;
use App\Models\EventParticipant;
use App\Models\ParticipantReceipt;
use App\Models\ReceiptItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Card Statistik
        |--------------------------------------------------------------------------
        */

        $totalEvent = Event::count();

        $totalItem = EventItem::count();

        $totalParticipant = EventParticipant::count();

        $totalDistributed = ParticipantReceipt::count();

        /*
        |--------------------------------------------------------------------------
        | Progress Distribusi
        |--------------------------------------------------------------------------
        */

        $distributionPercentage = 0;

        if ($totalParticipant > 0) {

            $distributionPercentage = round(
                ($totalDistributed / $totalParticipant) * 100,
                1
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Event Terbaru
        |--------------------------------------------------------------------------
        */

        $latestEvents = Event::latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Grafik Distribusi per Event
        |--------------------------------------------------------------------------
        */

        $eventDistribution = Event::select(
                'events.name',
                DB::raw('COUNT(participant_receipts.id) as total')
            )
            ->leftJoin(
                'event_participants',
                'events.id',
                '=',
                'event_participants.event_id'
            )
            ->leftJoin(
                'participant_receipts',
                'event_participants.id',
                '=',
                'participant_receipts.event_participant_id'
            )
            ->groupBy('events.id', 'events.name')
            ->orderBy('events.name')
            ->get();

        $eventChartLabels = $eventDistribution
            ->pluck('name');

        $eventChartData = $eventDistribution
            ->pluck('total');

        /*
        |--------------------------------------------------------------------------
        | Pie Chart Jenis Souvenir
        |--------------------------------------------------------------------------
        */

        $itemDistribution = ReceiptItem::select(
                'event_items.name',
                DB::raw('COUNT(receipt_items.id) as total')
            )
            ->join(
                'event_items',
                'receipt_items.event_item_id',
                '=',
                'event_items.id'
            )
            ->groupBy(
                'event_items.id',
                'event_items.name'
            )
            ->orderByDesc('total')
            ->get();

        $itemChartLabels = $itemDistribution
            ->pluck('name');

        $itemChartData = $itemDistribution
            ->pluck('total');

        /*
        |--------------------------------------------------------------------------
        | Top 5 Souvenir
        |--------------------------------------------------------------------------
        */

        $topSouvenirs = $itemDistribution
            ->take(5);

        /*
|--------------------------------------------------------------------------
| Mini Dashboard Statistics
|--------------------------------------------------------------------------
*/

$remainingParticipant = max(
    $totalParticipant - $totalDistributed,
    0
);

$mostPopularSouvenir = ReceiptItem::select(
        'event_items.name',
        DB::raw('COUNT(*) as total')
    )
    ->join(
        'event_items',
        'receipt_items.event_item_id',
        '=',
        'event_items.id'
    )
    ->groupBy(
        'event_items.id',
        'event_items.name'
    )
    ->orderByDesc('total')
    ->first();

$totalSouvenirDistributed = ReceiptItem::count();

$averagePerEvent = round(
    $totalSouvenirDistributed / max($totalEvent, 1),
    1
);

/*
|--------------------------------------------------------------------------
| Percentage Statistics
|--------------------------------------------------------------------------
*/

$remainingPercentage = round(
    ($remainingParticipant / max($totalParticipant, 1)) * 100,
    1
);

$averagePercentage = round(
    ($averagePerEvent / max($totalSouvenirDistributed, 1)) * 100,
    1
);

$favoritePercentage = 0;

if ($mostPopularSouvenir) {

    $favoritePercentage = round(
        ($mostPopularSouvenir->total / max($totalSouvenirDistributed, 1)) * 100,
        1
    );

}

        /*
        |--------------------------------------------------------------------------
        | Aktivitas Terbaru
        |--------------------------------------------------------------------------
        */

        $recentActivities = ParticipantReceipt::with([
                'participant',
                'receiptItems.item'
            ])
            ->latest('received_at')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard.index',
            compact(

                'totalEvent',
                'totalItem',
                'totalParticipant',
                'totalDistributed',

                'distributionPercentage',

                'latestEvents',

                'eventChartLabels',
                'eventChartData',

                'itemChartLabels',
                'itemChartData',

                'topSouvenirs',

                'remainingParticipant',
                'remainingPercentage',

                'mostPopularSouvenir',
                'favoritePercentage',

                'totalSouvenirDistributed',

                'averagePerEvent',
                'averagePercentage',

                'recentActivities',

            )
        );
    }
}
