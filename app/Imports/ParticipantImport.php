<?php

namespace App\Imports;

use App\Models\EventParticipant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ParticipantImport implements ToCollection
{
    protected $eventId;
    protected $participantType;

    public function __construct($eventId, $participantType)
    {
        $this->eventId = $eventId;
        $this->participantType = $participantType;
    }

    public function collection(Collection $rows)
    {
        // Lewati baris heading
        $rows->shift();

        foreach ($rows as $row) {

            // Lewati baris kosong
            if (empty($row[0]) && empty($row[1])) {
                continue;
            }

            EventParticipant::create([

                'event_id' => $this->eventId,

                // sementara pakai UUID agar unik
                'code' => 'EVP-' . strtoupper(Str::random(8)),

                'participant_code' => trim($row[0]),
                'name'             => trim($row[1]),
                'email'            => trim($row[2]),
                'phone'            => trim($row[3]),
                'campus'           => trim($row[4]),

                'faculty'          => null,
                'department'       => null,
                'position'         => null,

                'participant_type' => $this->participantType,

                'is_manual'        => false,
                'souvenir_status'  => false,
                'created_by'       => Auth::id(),

            ]);

        }
    }
}
