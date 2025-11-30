<?php

namespace App\Services\RabbitMQ;

use App\DTO\CallEventData;

interface CallEventPublisherInterface
{
    public function publish(CallEventData $event, int $logId): void;
}
