<?php

/**
 * Setup Test Script
 * @phpstan-ignore-next-line
 */

require __DIR__.'/../vendor/autoload.php';

echo "=== Notification Consumer Setup Test ===\n\n";

// Test 1: Check PHP version
echo "✓ PHP Version: " . PHP_VERSION . "\n";

// Test 2: Check vendor directory
if (is_dir(__DIR__.'/../vendor')) {
    echo "✓ Vendor directory exists\n";
} else {
    echo "✗ Vendor directory missing\n";
}

// Test 3: Check php-amqplib
if (class_exists('PhpAmqpLib\Connection\AMQPStreamConnection')) {
    echo "✓ php-amqplib loaded successfully\n";
} else {
    echo "✗ php-amqplib not found\n";
}

// Test 4: Check APP classes
$classes = [
    'App\Models\Message',
    'App\Services\NotificationService',
    'App\Repositories\RabbitMQRepository',
];

foreach ($classes as $class) {
    if (class_exists($class)) {
        echo "✓ {$class} loaded\n";
    } else {
        echo "✗ {$class} not found\n";
    }
}

// Test 5: Check .env file  
$envFile = __DIR__.'/../.env';
if (file_exists($envFile)) {
    echo "✓ .env file exists\n";
    
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            putenv(trim($line));
        }
    }
    
    echo "  - RABBITMQ_HOST: " . (getenv('RABBITMQ_HOST') ?: 'localhost') . "\n";
    echo "  - RABBITMQ_PORT: " . (getenv('RABBITMQ_PORT') ?: '5672') . "\n";
    echo "  - RABBITMQ_USER: " . (getenv('RABBITMQ_USER') ?: 'guest') . "\n";
    echo "  - RABBITMQ_EXCHANGE_NAME: " . (getenv('RABBITMQ_EXCHANGE_NAME') ?: 'notifications') . "\n";
} else {
    echo "✗ .env file missing\n";
}

// Test 6: Test RabbitMQ Connection
echo "\n✓ Attempting RabbitMQ connection test...\n";
try {
    $host = getenv('RABBITMQ_HOST') ?: 'localhost';
    $port = getenv('RABBITMQ_PORT') ?: 5672;
    $user = getenv('RABBITMQ_USER') ?: 'guest';
    $password = getenv('RABBITMQ_PASSWORD') ?: 'guest';
    $vhost = getenv('RABBITMQ_VHOST') ?: '/';
    
    $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
        $host,
        $port,
        $user,
        $password,
        $vhost
    );
    
    echo "✓ Connected to RabbitMQ at {$host}:{$port}\n";
    $connection->close();
} catch (\Exception $e) {
    echo "⚠ RabbitMQ connection failed: {$e->getMessage()}\n";
    echo "  Make sure RabbitMQ is running!\n";
    echo "  Docker: docker run -d --name rabbitmq -p 5672:5672 rabbitmq:3\n";
}

echo "\n=== Setup Test Complete ===\n";
echo "\nTo start consumers, run:\n";
echo "  php scripts/email-consumer.php\n";
echo "  php scripts/sms-consumer.php\n";
echo "  php scripts/fcm-consumer.php\n";
