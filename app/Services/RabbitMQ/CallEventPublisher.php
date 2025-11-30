<?php

namespace App\Services\RabbitMQ;

use App\DTO\CallEventData;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;
use Throwable;

class CallEventPublisher implements CallEventPublisherInterface
{
    public function publish(CallEventData $event, int $logId): void
    {
        $queueName = env('RABBITMQ_QUEUE', 'call-events');

        $payload = $event->toArray();
        $payload['log_id'] = $logId;

        try {
            $connection = new AMQPStreamConnection(
                env('RABBITMQ_HOST', '127.0.0.1'),
                env('RABBITMQ_PORT', default: 5672),
                env('RABBITMQ_USER', 'guest'),
                env('RABBITMQ_PASSWORD', default: 'guest'),
                env('RABBITMQ_VHOST', '/')
            );

            $channel = $connection->channel();

            $channel->queue_declare(
                $queueName,
                false,
                true,
                false,
                false
            );

            $messageBody = json_encode($payload);

            $message = new AMQPMessage(
                $messageBody,
                [
                    'content_type'  => 'application/json',
                    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                ]
            );

            $channel->basic_publish($message, '', $queueName);

            $channel->close();
            $connection->close();
        } catch (Throwable $e) {
            Log::error('RabbitMQ publish failed', context: [
                'error'   => $e->getMessage(),
                'payload' => $payload,
            ]);

            throw $e;
        }
    }
}
