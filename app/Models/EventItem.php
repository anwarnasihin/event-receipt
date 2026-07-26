<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventItem extends Model
{
    protected $fillable = [
        'event_id',
        'code',
        'name',
        'qty',
        'active'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    public function receiptItems()
    {
        return $this->hasMany(ReceiptItem::class);
    }
}
