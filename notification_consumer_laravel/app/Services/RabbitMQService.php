<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * RabbitMQ Service Helper
 * @phpstan-ignore-next-line
 */
class RabbitMQService
{
    protected static ?AMQPStreamConnection $connection = null;

    /**
     * Get RabbitMQ Connection
     *
     * @return AMQPStreamConnection
     * @throws \Exception
     */
    public static function getConnection(): AMQPStreamConnection
    {
        if (self::$connection === null) {
            $config = config('rabbitmq');

            self::$connection = new AMQPStreamConnection(
                $config['rabbitmq']['host'],
                $config['rabbitmq']['port'],
                $config['rabbitmq']['user'],
                $config['rabbitmq']['password'],
                $config['rabbitmq']['vhost']
            );
        }

        return self::$connection;
    }

    /**
     * Close Connection
     *
     * @return void
     */
    public static function closeConnection(): void
    {
        if (self::$connection !== null) {
            self::$connection->close();
            self::$connection = null;
        }
    }
}
