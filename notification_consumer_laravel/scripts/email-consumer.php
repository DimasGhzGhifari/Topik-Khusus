<?php

require __DIR__.'/../vendor/autoload.php';

use App\Repositories\RabbitMQRepository;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Email Consumer Script
 * @phpstan-ignore-next-line
 */

// Load environment variables from .env
$envFile = __DIR__.'/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            putenv(trim($line));
        }
    }
}

// Get configuration from environment
$host = getenv('RABBITMQ_HOST') ?: 'localhost';
$port = getenv('RABBITMQ_PORT') ?: 5672;
$user = getenv('RABBITMQ_USER') ?: 'guest';
$password = getenv('RABBITMQ_PASSWORD') ?: 'guest';
$vhost = getenv('RABBITMQ_VHOST') ?: '/';
$exchangeName = getenv('RABBITMQ_EXCHANGE_NAME') ?: 'notifications';

try {
    echo "[EMAIL] Connecting to RabbitMQ at {$host}:{$port}...\n";
    
    $connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
    $repository = new RabbitMQRepository($connection, $exchangeName);

    echo "[EMAIL] Connected successfully!\n";
    echo "[EMAIL] Starting Email Consumer...\n";
    
    $repository->consumeEmailMessages('EMAIL');

} catch (\Exception $e) {
    echo "[ERROR] {$e->getMessage()}\n";
    exit(1);
}
