<?php

namespace App\Jobs;

use App\DTO\CallEventData;
use App\Services\RabbitMQ\CallEventPublisherInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class PublishCallEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public int $backoff = 30;

    public function __construct(
        public array $eventData,
        public int $logId,
    ) {}

    public function handle(CallEventPublisherInterface $publisher): void
    {
        $event = CallEventData::fromArray($this->eventData);

        $publisher->publish($event, $this->logId);
    }

    public function failed(Throwable $e): void
    {
        \Log::error('PublishCallEvent job permanently failed', [
            'log_id' => $this->logId,
            'error'  => $e->getMessage(),
        ]);
    }
}
