<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    private function generateCode()
    {
        $lastEvent = Event::orderBy('id', 'desc')->first();

        if (!$lastEvent) {
            return 'EVT0001';
        }

        $lastNumber = (int) substr($lastEvent->code, 3);

        return 'EVT' . str_pad(
            $lastNumber + 1,
            4,
            '0',
            STR_PAD_LEFT
        );
    }

    public function index()
    {
        $events = Event::latest()->get();

        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Event::create([
            'code' => $this->generateCode(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'event_date' => $request->event_date,
            'location' => $request->location,
            'description' => $request->description,

            // Event baru otomatis aktif
            'status' => true,

            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil dibuat.');
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $event->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'event_date' => $request->event_date,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil diupdate.');
    }

    /**
     * Aktif / Nonaktif Event
     */
    public function toggleStatus(Event $event)
    {
        $event->update([
            'status' => !$event->status
        ]);

        return redirect()
            ->route('events.index')
            ->with(
                'success',
                $event->status
                    ? 'Event berhasil diaktifkan.'
                    : 'Event berhasil dinonaktifkan.'
            );
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
