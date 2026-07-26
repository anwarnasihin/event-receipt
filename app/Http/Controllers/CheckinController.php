<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function show($code)
    {
        $participant = EventParticipant::where('code',$code)
            ->firstOrFail();

        return view(
            'checkin.show',
            compact('participant')
        );
    }

    public function store(Request $request,$code)
    {
        $participant = EventParticipant::where('code',$code)
            ->firstOrFail();

        if(!$participant->attendance_status){

            $participant->update([

                'attendance_status'=>true,

                'checkin_at'=>now(),

            ]);

        }

        return redirect()

            ->route(
                'participant.checkin',
                $participant->code
            )

            ->with(
                'success',
                'Check In berhasil.'
            );

    }
}
