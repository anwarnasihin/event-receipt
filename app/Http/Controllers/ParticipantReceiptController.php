<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\EventItem;
use App\Models\ParticipantReceipt;
use App\Models\ReceiptItem;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;




class ParticipantReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::orderBy('name')->get();

        return view('receipt.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|exists:event_participants,id',
            'items'          => 'required|array|min:1',
            'items.*'        => 'exists:event_items,id',
            'photo'          => 'required|string',
        ]);

        DB::beginTransaction();
        $agent = new Agent();

        try {

            // ==========================
            // CEK SUDAH MENERIMA SOUVENIR?
            // ==========================
            $participant = EventParticipant::findOrFail($request->participant_id);

            if ($participant->souvenir_status) {

                return response()->json([
                    'success' => false,
                    'message' => 'Peserta sudah menerima souvenir.'
                ], 422);

            }

            // ==========================
            // SIMPAN FOTO
            // ==========================

            $photo = preg_replace('/^data:image\/\w+;base64,/', '', $request->photo);
            $photo = str_replace(' ', '+', $photo);

            $image = base64_decode($photo);

            $fileName = 'receipt_' .
                now()->format('YmdHis') .
                '_' .
                Str::random(8) .
                '.jpg';

            Storage::disk('public')->put(
                'receipts/' . $fileName,
                $image
            );

            // ==========================
            // SIMPAN RECEIPT
            // ==========================

            $receipt = ParticipantReceipt::create([
                'event_participant_id' => $participant->id,
                'user_id' => Auth::id(),
                'photo' => 'receipts/' . $fileName,
                'received_at' => now(),
                'ip_address' => $request->ip(),
                'browser' => $agent->browser(),
                'operating_system' => $agent->platform(),
                'user_agent' => $request->userAgent(),
                'notes' => null
            ]);

            // ==========================
            // SIMPAN DETAIL SOUVENIR
            // ==========================

            foreach ($request->items as $itemId) {

                $item = EventItem::findOrFail($itemId);

                // Cek stok
                if ($item->qty <= 0) {
                    throw new \Exception("Stok {$item->name} sudah habis.");
                }

                ReceiptItem::create([
                    'participant_receipt_id' => $receipt->id,
                    'event_item_id' => $itemId
                ]);

                $item->decrement('qty');

            }

            // ==========================
            // UPDATE STATUS PESERTA
            // ==========================

            $participant->update([

                'souvenir_status' => true,

                'souvenir_taken_at' => now()

            ]);

            DB::commit();

            return response()->json([

                'success' => true,

                'message' => 'Souvenir berhasil diserahkan.'

            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $items = EventItem::where('event_id', $event->id)
            ->where('active', 1)
            ->where('qty', '>', 0)   // <-- Tambahkan ini
            ->orderBy('name')
            ->get();

        return view(
            'receipt.show',
            compact('event', 'items')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function search(Request $request, Event $event)
    {
        $keyword = $request->keyword;

        $participant = EventParticipant::where('event_id', $event->id)
            ->where(function ($query) use ($keyword) {

                $query->where('participant_code', 'like', "%{$keyword}%")
                    ->orWhere('name', 'like', "%{$keyword}%");

            })
            ->first();

        if (!$participant) {

            return response()->json([
                'success' => false,
                'message' => 'Peserta tidak ditemukan.'
            ]);

        }

        return response()->json([
            'success' => true,
            'participant' => $participant
        ]);
    }

    public function items(Event $event)
    {
        $items = EventItem::where('event_id', $event->id)
            ->where('active', 1)
            ->where('qty', '>', 0)
            ->orderBy('name')
            ->get();

        return response()->json($items);
    }
}
