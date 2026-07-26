<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptItem extends Model
{
    protected $fillable = [
        'participant_receipt_id',
        'event_item_id'
    ];

    public function receipt()
    {
        return $this->belongsTo(
            ParticipantReceipt::class,
            'participant_receipt_id'
        );
    }

    public function item()
    {
        return $this->belongsTo(
            EventItem::class,
            'event_item_id'
        );
    }
}
