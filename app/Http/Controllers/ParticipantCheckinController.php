<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\ParticipantCheckin;
use Illuminate\Support\Facades\Auth;

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
        return view('checkin.show', compact('event'));
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
}
