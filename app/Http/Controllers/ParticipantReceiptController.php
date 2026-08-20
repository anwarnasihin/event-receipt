<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\EventItem;
use App\Models\ParticipantReceipt;
use App\Models\ReceiptItem;
use App\Mail\SouvenirReceiptMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Jenssegers\Agent\Agent;




class ParticipantReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::where('status', true)->orderBy('name')->get();

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
    $validator = Validator::make($request->all(), [
        'participant_id' => 'required|exists:event_participants,id',
        'name' => 'required|string|max:255',
        'participant_code' => [
            'required',
            'string',
            'max:255',
            Rule::unique('event_participants', 'participant_code')
                ->ignore($request->participant_id),
        ],
        'campus' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:50',
        'items' => 'required|array|min:1',
        'items.*' => 'exists:event_items,id',
        'photo' => 'required|string',
    ]);

    if ($validator->fails()) {

    $errors = $validator->errors()->all();

    return response()->json([
        'success' => false,
        'message' => implode(' | ', $errors),
        'errors' => $validator->errors(),
    ], 422);
}

    $agent = new Agent();

    DB::beginTransaction();

    try {

        $participant = EventParticipant::findOrFail(
            $request->participant_id
        );

        if ($participant->souvenir_status) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Peserta sudah menerima souvenir.'
            ], 422);
        }

        $participant->update([
            'name' => $request->name,
            'participant_code' => $request->participant_code,
            'campus' => $request->campus,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $photo = preg_replace(
            '/^data:image\/\w+;base64,/',
            '',
            $request->photo
        );

        $photo = str_replace(' ', '+', $photo);

        $image = base64_decode($photo);

        if ($image === false) {
            throw new \Exception('Foto tidak dapat diproses.');
        }

        $fileName =
            'receipt_' .
            now()->format('YmdHis') .
            '_' .
            Str::random(8) .
            '.jpg';

        Storage::disk('public')->put(
            'receipts/' . $fileName,
            $image
        );

        $receipt = ParticipantReceipt::create([
            'event_participant_id' => $participant->id,
            'user_id' => Auth::id(),
            'photo' => 'receipts/' . $fileName,
            'received_at' => now(),
            'ip_address' => $request->ip(),
            'browser' => $agent->browser(),
            'operating_system' => $agent->platform(),
            'user_agent' => $request->userAgent(),
            'notes' => null,
        ]);

        foreach ($request->items as $itemId) {

            $item = EventItem::findOrFail($itemId);

            if ($item->qty <= 0) {
                throw new \Exception(
                    "Stok {$item->name} sudah habis."
                );
            }

            ReceiptItem::create([
                'participant_receipt_id' => $receipt->id,
                'event_item_id' => $itemId,
            ]);

            $item->decrement('qty');
        }

        $participant->update([
            'souvenir_status' => true,
            'souvenir_taken_at' => now(),
        ]);

        DB::commit();

        $emailSent = false;

        try {

            if (!empty($participant->email)) {

                $receipt->load([
                    'participant.event',
                    'user',
                    'receiptItems.item'
                ]);

                Mail::to($participant->email)
                    ->send(
                        new SouvenirReceiptMail($receipt)
                    );

                $emailSent = true;
            }

        } catch (\Throwable $mailException) {

            report($mailException);
        }

        return response()->json([
            'success' => true,
            'message' => $emailSent
                ? 'Souvenir berhasil diserahkan dan tanda terima telah dikirim ke email peserta.'
                : 'Souvenir berhasil diserahkan, tetapi tanda terima email belum berhasil dikirim.',
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
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

    public function pdf(ParticipantReceipt $receipt)
    {
        $receipt->load([
            'participant.event',
            'user',
            'receiptItems.item',
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'receipt.souvenir-pdf',
            compact('receipt')
        );

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream(
            'Tanda_Terima_Souvenir.pdf'
        );
    }
}
