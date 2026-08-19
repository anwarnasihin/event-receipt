<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'code',
        'name',
        'slug',
        'event_date',
        'location',
        'description',
        'status',
        'created_by'
    ];

    protected $casts = [
        'status' => 'boolean',
        'event_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(EventItem::class);
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }
}
