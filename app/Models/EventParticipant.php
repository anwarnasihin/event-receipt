<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ParticipantReceipt;

class EventParticipant extends Model
{
    protected $fillable = [

        'event_id',
        'code',
        'participant_code',
        'name',
        'email',
        'phone',
        'campus',
        'faculty',
        'department',
        'position',
        'participant_type',
        'is_manual',
        'souvenir_status',
        'souvenir_taken_at',
        'notes',
        'created_by',
    ];
    protected $casts = [
        'is_manual' => 'boolean',
        'souvenir_status' => 'boolean',
        'souvenir_taken_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function receipts()
    {
        return $this->hasMany(ParticipantReceipt::class);
    }

    public function checkin()
    {
        return $this->hasOne(ParticipantCheckin::class);
    }
}
