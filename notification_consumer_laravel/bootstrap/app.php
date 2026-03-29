<?php

/**
 * Bootstrap configuration for Notification Consumer
 * This is a minimal setup without full Laravel framework
 */

// Define base path
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Load environment variables
$dotenv = BASE_PATH . '/.env';
if (file_exists($dotenv)) {
    $lines = file($dotenv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            putenv(trim($line));
        }
    }
}

// Simple service locator
class Container
{
    private static array $services = [];

    public static function set($key, $value)
    {
        self::$services[$key] = $value;
    }

    public static function get($key)
    {
        return self::$services[$key] ?? null;
    }
}

// Register services
Container::set('config.rabbitmq', [
    'host' => getenv('RABBITMQ_HOST') ?: 'localhost',
    'port' => getenv('RABBITMQ_PORT') ?: 5672,
    'user' => getenv('RABBITMQ_USER') ?: 'guest',
    'password' => getenv('RABBITMQ_PASSWORD') ?: 'guest',
    'vhost' => getenv('RABBITMQ_VHOST') ?: '/',
    'exchange_name' => getenv('RABBITMQ_EXCHANGE_NAME') ?: 'notifications',
]);

return true;
