<?php

require __DIR__.'/../vendor/autoload.php';

echo "Testing consumer script without RabbitMQ...\n";
echo "Loading classes...\n";

// Test class loading
try {
    $message = new \App\Models\Message([
        'order_id' => '123',
        'user_id' => '456',
        'content' => 'Test message',
        'timestamp' => date('c')
    ]);
    
    echo "✓ Message model loaded and instantiated\n";
    echo "  Message: " . json_encode($message->toArray()) . "\n\n";
    
    $notificationService = new \App\Services\NotificationService();
    echo "✓ NotificationService loaded\n\n";
    
    echo "=== Testing Notification Sending ===\n";
    echo "[1] Testing Email...\n";
    $notificationService->sendEmail($message);
    
    echo "[2] Testing SMS...\n"; 
    $notificationService->sendSms($message);
    
    echo "[3] Testing FCM...\n";
    $notificationService->sendFcm($message);
    
    echo "\n✓ All components working correctly!\n";
    
} catch (\Exception $e) {
    echo "✗ Error: {$e->getMessage()}\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString();
    exit(1);
}

echo "\n=== Setup Complete ===\n";
echo "Consumers are ready to run. Start them with:\n";
echo "  php scripts/email-consumer.php\n";
echo "  php scripts/sms-consumer.php\n";
echo "  php scripts/fcm-consumer.php\n";
