<?php

namespace App\Repositories;

use App\DTO\CallEventData;
use App\Models\CallEventLog;
use Carbon\Carbon;

class EloquentCallEventLogRepository implements CallEventLogRepositoryInterface
{
    public function logEvent(CallEventData $event): CallEventLog
{
    return CallEventLog::firstOrCreate(
        [
            'call_id'         => $event->callId,
            'event_type'      => $event->eventType->value, 
            'event_timestamp' => $event->timestamp,        
        ],
        [
            'payload'      => $event->toArray(),
            'created_time' => now(),
        ]
    );

}

}
