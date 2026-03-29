<?php

namespace App\Repositories;

use App\Models\Message;
use App\Services\NotificationService;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * RabbitMQ Repository for consuming messages
 * 
 * @phpstan-type Connection \PhpAmqpLib\Connection\AMQPStreamConnection
 * @phpstan-type Message \PhpAmqpLib\Message\AMQPMessage
 */
class RabbitMQRepository
{
    /** @var \PhpAmqpLib\Connection\AMQPStreamConnection */
    protected AMQPStreamConnection $connection;
    /** @var string */
    protected string $exchangeName;
    /** @var \App\Services\NotificationService */
    protected NotificationService $notificationService;

    /**
     * @param \PhpAmqpLib\Connection\AMQPStreamConnection $connection
     * @param string $exchangeName
     */
    public function __construct(AMQPStreamConnection $connection, string $exchangeName)
    {
        $this->connection = $connection;
        $this->exchangeName = $exchangeName;
        $this->notificationService = new NotificationService();
    }

    /**
     * Consume Email Messages
     *
     * @param string $serviceName
     * @return void
     */
    public function consumeEmailMessages(string $serviceName): void
    {
        $channel = $this->connection->channel();

        // Declare fanout exchange
        $channel->exchange_declare(
            $this->exchangeName,
            'fanout',
            false,  // passive
            true,   // durable
            false   // auto_delete
        );

        // Declare and bind queue
        list($queue) = $channel->queue_declare($serviceName, false, true, false, false);
        $channel->queue_bind($queue, $this->exchangeName);

        // Consume messages
        $channel->basic_consume(
            $queue,
            $serviceName,
            false,  // no_local
            false,  // no_ack
            false,  // exclusive
            false,  // nowait
            /** @param \PhpAmqpLib\Message\AMQPMessage $msg */
            function (AMQPMessage $msg) {
                $this->processMessage($msg, 'email');
            }
        );

        echo "[{$serviceName}] Listening for messages...\n";

        try {
            $channel->consume();
        } catch (\Exception $e) {
            echo "[{$serviceName}] Error: {$e->getMessage()}\n";
        }
    }

    /**
     * Consume SMS Messages
     *
     * @param string $serviceName
     * @return void
     */
    public function consumeSmsMessages(string $serviceName): void
    {
        $channel = $this->connection->channel();

        // Declare fanout exchange
        $channel->exchange_declare(
            $this->exchangeName,
            'fanout',
            false,  // passive
            true,   // durable
            false   // auto_delete
        );

        // Declare and bind queue
        list($queue) = $channel->queue_declare($serviceName, false, true, false, false);
        $channel->queue_bind($queue, $this->exchangeName);

        // Consume messages
        $channel->basic_consume(
            $queue,
            $serviceName,
            false,  // no_local
            false,  // no_ack
            false,  // exclusive
            false,  // nowait
            /** @param \PhpAmqpLib\Message\AMQPMessage $msg */
            function (AMQPMessage $msg) {
                $this->processMessage($msg, 'sms');
            }
        );

        echo "[{$serviceName}] Listening for messages...\n";

        try {
            $channel->consume();
        } catch (\Exception $e) {
            echo "[{$serviceName}] Error: {$e->getMessage()}\n";
        }
    }

    /**
     * Consume FCM Messages
     *
     * @param string $serviceName
     * @return void
     */
    public function consumeFcmMessages(string $serviceName): void
    {
        $channel = $this->connection->channel();

        // Declare fanout exchange
        $channel->exchange_declare(
            $this->exchangeName,
            'fanout',
            false,  // passive
            true,   // durable
            false   // auto_delete
        );

        // Declare and bind queue
        list($queue) = $channel->queue_declare($serviceName, false, true, false, false);
        $channel->queue_bind($queue, $this->exchangeName);

        // Consume messages
        $channel->basic_consume(
            $queue,
            $serviceName,
            false,  // no_local
            false,  // no_ack
            false,  // exclusive
            false,  // nowait
            /** @param \PhpAmqpLib\Message\AMQPMessage $msg */
            function (AMQPMessage $msg) {
                $this->processMessage($msg, 'fcm');
            }
        );

        echo "[{$serviceName}] Listening for messages...\n";

        try {
            $channel->consume();
        } catch (\Exception $e) {
            echo "[{$serviceName}] Error: {$e->getMessage()}\n";
        }
    }

    /**
     * Process incoming message
     *
     * @param \PhpAmqpLib\Message\AMQPMessage $msg
     * @param string $type
     * @return void
     */
    protected function processMessage(AMQPMessage $msg, string $type): void
    {
        try {
            $body = json_decode($msg->body, true);

            $message = new Message($body);

            echo "[" . strtoupper($type) . "] Received message: " . json_encode($body) . "\n";

            // Send notification based on type
            match ($type) {
                'email' => $this->notificationService->sendEmail($message),
                'sms' => $this->notificationService->sendSms($message),
                'fcm' => $this->notificationService->sendFcm($message),
            };

            // Acknowledge the message
            $msg->ack();
        } catch (\Exception $e) {
            echo "[ERROR] Failed to process message: {$e->getMessage()}\n";
            $msg->nack();
        }
    }
}
