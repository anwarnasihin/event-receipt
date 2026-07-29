<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\ParticipantCheckin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ParticipantCheckinController extends Controller
{
    /**
     * Halaman pilih event
     */
    public function index()
    {
        $events = Event::orderBy('name')->get();

        return view('checkin.index', compact('events'));
    }

    /**
     * Halaman absensi
     */
    public function show(Event $event)
{
    $totalParticipants = EventParticipant::where('event_id', $event->id)->count();

    $checkedIn = ParticipantCheckin::whereHas('eventParticipant', function ($query) use ($event) {
        $query->where('event_id', $event->id);
    })->count();

    $notCheckedIn = $totalParticipants - $checkedIn;

    return view('checkin.show', compact(
        'event',
        'totalParticipants',
        'checkedIn',
        'notCheckedIn'
    ));
}

    /**
     * Cari peserta
     */
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

    public function participants(Event $event)
{
    $participants = EventParticipant::with('checkin')
        ->where('event_id', $event->id)
        ->orderBy('participant_code')
        ->get();

    return response()->json($participants);
}

    /**
     * Simpan Check In
     */
    public function store(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|exists:event_participants,id',
        ]);

        $participant = EventParticipant::findOrFail($request->participant_id);

        // Sudah check in?
        $already = ParticipantCheckin::where(
            'event_participant_id',
            $participant->id
        )->exists();

        if ($already) {

            return response()->json([
                'success' => false,
                'message' => 'Peserta sudah melakukan Check In.'
            ], 422);

        }

        ParticipantCheckin::create([
            'event_participant_id' => $participant->id,
            'checkin_at' => now(),
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check In berhasil.'
        ]);
    }

    public function storeManual(Request $request, Event $event)
    {
        $request->validate([
            'participant_code' => 'required|max:100',
            'name'             => 'required|max:255',
            'email'            => 'nullable|email|max:255',
            'phone'            => 'nullable|max:30',
            'campus'           => 'nullable|max:255',
            'participant_type' => 'required|in:Dosen,Staff,Mahasiswa,Guest',
        ]);

        DB::beginTransaction();

        try {

            $participant = EventParticipant::create([
                'event_id' => $event->id,
                'code'             => 'EVP-' . strtoupper(Str::random(8)),
                'participant_code' => $request->participant_code,
                'name'             => $request->name,
                'email'            => $request->email,
                'phone'            => $request->phone,
                'campus'           => $request->campus,
                'participant_type' => $request->participant_type,
                'is_manual'        => true,
                'souvenir_status'  => false,
                'created_by'       => Auth::id(),
            ]);

            // BAGIAN INI LANGSUNG CHECK-IN otomatis setelah peserta ditambahkan
            /*
            ParticipantCheckin::create([
                'event_participant_id' => $participant->id,
                'checkin_at'           => now(),
                'created_by'           => Auth::id(),
            ]);
            */

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Peserta berhasil ditambahkan dan langsung Check In.'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);

        }
    }
}
