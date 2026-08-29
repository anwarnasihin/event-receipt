<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventItem;
use Illuminate\Http\Request;

class EventItemController extends Controller
{
    private function generateCode()
{
    $lastItem = EventItem::orderBy('id', 'desc')->first();

    if (!$lastItem) {
        return 'ITM0001';
    }

    $lastNumber = (int) substr($lastItem->code, 3);

    return 'ITM' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
}
    /**
     * Menampilkan daftar item berdasarkan event
     */
    public function index(Event $event)
    {
        $items = $event->items()->latest()->get();

        return view('events.items.index', compact('event', 'items'));
    }

    /**
     * Form tambah item
     */
    public function create(Event $event)
    {
        return view('events.items.create', compact('event'));
    }

    /**
     * Simpan item
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'qty' => 'required|integer|min:0',
        ]);

    EventItem::create([
        'event_id' => $event->id,
        'code'     => $this->generateCode(),
        'name'     => $request->name,
        'qty'      => $request->qty,
        'active'   => $request->has('active'),
    ]);

    return redirect()
        ->route('events.items.index', $event)
        ->with('success', 'Item berhasil ditambahkan.');
    }

    /**
     * Form edit
     */
    public function edit(Event $event, EventItem $item)
    {
        return view('events.items.edit', compact('event', 'item'));
    }

    /**
     * Update item
     */
    public function update(Request $request, Event $event, EventItem $item)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'qty' => 'required|integer|min:0',
        ]);

    $item->update([
        'name'   => $request->name,
        'qty'    => $request->qty,
        'active' => $request->has('active'),
    ]);

    return redirect()
        ->route('events.items.index', $event)
        ->with('success', 'Item berhasil diupdate.');
    }

    /**
     * Hapus item
     */
    public function destroy(Event $event, EventItem $item)
    {
        $item->delete();

    return redirect()
        ->route('events.items.index', $event)
        ->with('success', 'Item berhasil dihapus.');
    }
}
