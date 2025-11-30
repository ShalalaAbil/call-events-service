<?php


namespace App\Services;


use App\DTO\CallEventData;
use App\Enums\CallEventType;
use App\Jobs\PublishCallEvent;
use App\Repositories\CallEventLogRepositoryInterface;

class CallEventService
{
    public function __construct(
        protected CallEventLogRepositoryInterface $repository,
    ) {}

    public function handleIncomingEvent(array $validatedData): void
    {
        $event = CallEventData::fromArray($validatedData);

        // DB-yə logla
        $log = $this->repository->logEvent($event);

        // SİNXRON RabbitMQ əvəzinə: ASYNC Job
        PublishCallEvent::dispatch(
            $event->toArray(),
            $log->id
        );
    }
}

