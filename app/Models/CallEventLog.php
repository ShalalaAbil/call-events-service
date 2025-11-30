<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallEventLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'call_id',
        'event_type',
        'event_timestamp',
        'payload',
        'created_time',
    ];

    protected $casts = [
        'payload'        => 'array',
        'event_timestamp'=> 'datetime',
        'created_time'   => 'datetime',
    ];
}
