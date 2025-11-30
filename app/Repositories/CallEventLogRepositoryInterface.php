<?php

namespace App\Repositories;

use App\DTO\CallEventData;
use App\Models\CallEventLog;

interface CallEventLogRepositoryInterface
{
    public function logEvent(CallEventData $event): CallEventLog;
}
