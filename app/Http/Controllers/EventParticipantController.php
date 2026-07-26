<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EventParticipantController extends Controller
{
    /**
     * Generate Kode Peserta
     */
   private function generateCode()
{
    $last = EventParticipant::where('code', 'like', 'PRT%')
        ->orderByDesc('id')
        ->first();

    if (!$last) {
        return 'PRT0001';
    }

    $number = (int) str_replace('PRT', '', $last->code);

    return 'PRT' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
}
    /**
     * List Participant
     */
    public function index(Event $event)
    {
        $participants = $event->participants()
                              ->latest()
                              ->get();

        return view(
            'events.participants.index',
            compact('event', 'participants')
        );
    }

    /**
     * Detail Participant
     */
    public function show(Event $event, EventParticipant $participant)
    {
        return view(
            'events.participants.show',
            compact('event', 'participant')
        );
    }

    /**
     * Form Create
     */
    public function create(Event $event)
    {
        return view(
            'events.participants.create',
            compact('event')
        );
    }

    /**
     * Store Participant
     */
    public function store(Request $request, Event $event)
    {

        $request->validate([
            'participant_code' => [
            'required',
            'max:50',
            Rule::unique('event_participants')
                ->where(fn ($query) => $query->where('event_id', $event->id)),
        ],
            'participant_code' => 'required|max:50',
            'name'             => 'required|max:150',
            'email'            => 'required|email|max:150',
            'phone'            => 'required|max:30',
            'campus'           => 'required|max:150',
            'faculty'          => 'nullable|max:150',
            'department'       => 'nullable|max:150',
            'position'         => 'nullable|max:150',
            'participant_type' => 'required',
            'notes'            => 'nullable|string',
        ]);

        EventParticipant::create([
            'event_id'         => $event->id,
            'code'             => $this->generateCode(),
            'participant_code' => $request->participant_code,
            'name'             => $request->name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'campus'           => $request->campus,
            'faculty'          => $request->faculty,
            'department'       => $request->department,
            'position'         => $request->position,
            'participant_type' => $request->participant_type,
            'is_manual'        => true,
            'notes'            => $request->notes,
            'created_by' => Auth::id(),
        ]);


        return redirect()
            ->route('events.participants.index', $event)
            ->with('success', 'Peserta berhasil ditambahkan.');
    }

    /**
     * Form Edit
     */
    public function edit(Event $event, EventParticipant $participant)
    {
        return view(
            'events.participants.edit',
            compact('event', 'participant')
        );
    }

    /**
     * Update Participant
     */
    public function update(Request $request, Event $event, EventParticipant $participant)
    {
        $request->validate([
            'participant_code' => 'required|max:50',
            'name'             => 'required|max:150',
            'email'            => 'required|email|max:150',
            'phone'            => 'required|max:30',
            'campus'           => 'required|max:150',
            'faculty'          => 'nullable|max:150',
            'department'       => 'nullable|max:150',
            'position'         => 'nullable|max:150',
            'participant_type' => 'required',
            'notes'            => 'nullable|string',
        ]);

        $participant->update([
            'participant_code' => $request->participant_code,
            'name'             => $request->name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'campus'           => $request->campus,
            'faculty'          => $request->faculty,
            'department'       => $request->department,
            'position'         => $request->position,
            'participant_type' => $request->participant_type,
            'notes'            => $request->notes,
        ]);

        return redirect()
            ->route('events.participants.index', $event)
            ->with('success', 'Peserta berhasil diupdate.');
    }

    /**
     * Delete Participant
     */
    public function destroy(Event $event, EventParticipant $participant)
    {
        $participant->delete();

        return redirect()
            ->route('events.participants.index', $event)
            ->with('success', 'Peserta berhasil dihapus.');
    }
}
