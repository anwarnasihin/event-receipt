<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantReceipt extends Model
{
    protected $fillable = [
        'event_participant_id',
        'user_id',
        'photo',
        'received_at',
        'notes'
    ];

    public function participant()
    {
        return $this->belongsTo(EventParticipant::class, 'event_participant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receiptItems()
    {
        return $this->hasMany(ReceiptItem::class);
    }
}
