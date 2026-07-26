<?php

namespace App\Http\Controllers;

use App\Exports\ParticipantTemplateExport;
use App\Models\Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use App\Imports\ParticipantImport;

class EventParticipantImportController extends Controller
{
    public function create(Event $event)
    {
        return view('events.participants.import', compact('event'));
    }

    public function store(Request $request, Event $event)
{
    $request->validate([

        'participant_type' => 'required',

        'file' => 'required|mimes:xlsx,xls',

    ]);

    Excel::import(

        new ParticipantImport(
            $event->id,
            $request->participant_type
        ),

        $request->file('file')

    );

    return redirect()
        ->route('events.participants.index', $event)
        ->with('success', 'Data peserta berhasil diimport.');
}

public function template(Event $event)
{
    return Excel::download(
        new ParticipantTemplateExport(),
        'Participant_Template.xlsx'
    );
}
}
