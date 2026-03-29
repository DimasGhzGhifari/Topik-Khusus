<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    public function publish(array $message, string $exchange = null, string $routingKey = null): void
    {
        $exchange = $exchange ?? config('rabbitmq.exchange');
        $routingKey = $routingKey ?? config('rabbitmq.routing_key');

        $connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password')
        );

        $channel = $connection->channel();
        $channel->exchange_declare(
            $exchange,
            'fanout',
            false,
            true,
            false
        );

        $payload = json_encode($message, JSON_UNESCAPED_UNICODE);
        if ($payload === false) {
            throw new \RuntimeException('Unable to encode message to JSON.');
        }

        $amqpMessage = new AMQPMessage(
            $payload,
            ['content_type' => 'application/json']
        );

        $channel->basic_publish($amqpMessage, $exchange, $routingKey);
        $channel->close();
        $connection->close();
    }
}
