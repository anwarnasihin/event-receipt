<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantCheckin extends Model
{
    protected $fillable = [

        'event_participant_id',
        'checkin_at',
        'created_by',

    ];

    protected $casts = [

        'checkin_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */

    public function eventParticipant()
    {
        return $this->belongsTo(EventParticipant::class, 'event_participant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
