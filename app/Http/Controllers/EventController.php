<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    private function generateCode()
    {
        $lastEvent = Event::withTrashed()
            ->orderBy('id', 'desc')
            ->first();

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

    /**
     * Soft Delete Event
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil dipindahkan ke Recycle Bin.');
    }

    /**
     * ============================================================
     * RECYCLE BIN
     * ============================================================
     */

    /**
     * Menampilkan event yang sudah di-soft delete.
     */
    public function trash()
    {
        $events = Event::onlyTrashed()
            ->withCount('participants')
            ->orderByDesc('deleted_at')
            ->get();

        return view('events.trash', compact('events'));
    }

    /**
     * Restore event dari Recycle Bin.
     */
    public function restore($id)
    {
        $event = Event::onlyTrashed()->findOrFail($id);

        $event->restore();

        return redirect()
            ->route('events.trash')
            ->with(
                'success',
                'Event "' . $event->name . '" berhasil dipulihkan.'
            );
    }

    /**
     * Hapus event secara permanen.
     *
     * Semua data yang berhubungan dengan event
     * akan ikut terhapus melalui cascade database.
     *
     * File foto receipt dihapus secara manual dari storage.
     */
    public function forceDelete($id)
    {
        $event = Event::onlyTrashed()
            ->with([
                'participants.receipts'
            ])
            ->findOrFail($id);

        DB::beginTransaction();

        try {

            /*
             * ========================================================
             * HAPUS FILE FOTO RECEIPT
             * ========================================================
             */
            foreach ($event->participants as $participant) {

                foreach ($participant->receipts as $receipt) {

                    if (!empty($receipt->photo)) {

                        if (Storage::disk('public')->exists($receipt->photo)) {
                            Storage::disk('public')->delete($receipt->photo);
                        }

                    }
                }
            }

            /*
             * ========================================================
             * HAPUS EVENT
             * ========================================================
             *
             * Database akan menghapus otomatis:
             *
             * Event
             * ├── Event Items
             * ├── Event Participants
             * │     ├── Participant Checkins
             * │     └── Participant Receipts
             * │           └── Receipt Items
             *
             * karena foreign key menggunakan cascadeOnDelete().
             */

            $eventName = $event->name;
            $eventCode = $event->code;

            $event->forceDelete();

            DB::commit();

            return redirect()
                ->route('events.trash')
                ->with(
                    'success',
                    'Event ' . $eventCode . ' - ' . $eventName .
                    ' berhasil dihapus permanen.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return redirect()
                ->route('events.trash')
                ->with(
                    'error',
                    'Gagal menghapus event secara permanen. Tidak ada data yang dihapus.'
                );
        }
    }
}
