#!/usr/bin/env php
<?php

/**
 * Simple web server untuk testing notification consumer
 * Jalankan: php api-server.php
 * Akses: http://localhost:8001
 */

require __DIR__.'/vendor/autoload.php';

use App\Http\ApiController;

// Simple router
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/api', '', $path);

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$controller = new ApiController();

// Routes
$routes = [
    'GET' => [
        '/health' => function() use ($controller) {
            return $controller->health();
        },
        '/api/health' => function() use ($controller) {
            return $controller->health();
        },
    ],
    'POST' => [
        '/test/email' => function() use ($controller) {
            $data = json_decode(file_get_contents('php://input'), true);
            return $controller->testEmail($data);
        },
        '/test/sms' => function() use ($controller) {
            $data = json_decode(file_get_contents('php://input'), true);
            return $controller->testSms($data);
        },
        '/test/fcm' => function() use ($controller) {
            $data = json_decode(file_get_contents('php://input'), true);
            return $controller->testFcm($data);
        },
        '/api/test/email' => function() use ($controller) {
            $data = json_decode(file_get_contents('php://input'), true);
            return $controller->testEmail($data);
        },
        '/api/test/sms' => function() use ($controller) {
            $data = json_decode(file_get_contents('php://input'), true);
            return $controller->testSms($data);
        },
        '/api/test/fcm' => function() use ($controller) {
            $data = json_decode(file_get_contents('php://input'), true);
            return $controller->testFcm($data);
        },
    ]
];

// Match route
if (isset($routes[$method][$path])) {
    $response = $routes[$method][$path]();
    http_response_code(200);
    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Route not found: ' . $method . ' ' . $path]);
}
