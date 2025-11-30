<?php

namespace App\DTO;

use App\Enums\CallEventType;

class CallEventData
{
    public function __construct(
        public string $callId,
        public string $fromNumber,
        public string $toNumber,
        public CallEventType $eventType, 
        public string $timestamp,
        public ?int $duration,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            callId: $data['call_id'],
            fromNumber: $data['from_number'],
            toNumber: $data['to_number'],
            eventType: CallEventType::from($data['event_type']), 
            timestamp: $data['timestamp'],
            duration: $data['duration'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'call_id'     => $this->callId,
            'from_number' => $this->fromNumber,
            'to_number'   => $this->toNumber,
            'event_type'  => $this->eventType->value,
            'timestamp'   => $this->timestamp,
            'duration'    => $this->duration,
        ];
    }
}
