<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\CallEventLogRepositoryInterface;
use App\Repositories\EloquentCallEventLogRepository;
use App\Services\RabbitMQ\CallEventPublisherInterface;
use App\Services\RabbitMQ\CallEventPublisher;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            CallEventLogRepositoryInterface::class,
            EloquentCallEventLogRepository::class
        );

        $this->app->bind(
            CallEventPublisherInterface::class,
            CallEventPublisher::class
        );
    }

    public function boot(): void
    {
        //
    }
}
