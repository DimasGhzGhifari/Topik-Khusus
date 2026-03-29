<?php

namespace App\Http;

use App\Services\NotificationService;
use App\Models\Message;
use Exception;

class ApiController
{
    protected NotificationService $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    /**
     * Health check endpoint
     */
    public function health()
    {
        return [
            'status' => 'OK',
            'service' => 'Notification Consumer',
            'version' => '1.0.0',
            'timestamp' => date('c')
        ];
    }

    /**
     * Test send email notification
     */
    public function testEmail($data = null)
    {
        try {
            $message = new Message([
                'order_id' => $data['order_id'] ?? 'TEST-001',
                'user_id' => $data['user_id'] ?? 'USER-001',
                'content' => $data['content'] ?? 'Test email notification',
                'timestamp' => date('c')
            ]);

            $this->notificationService->sendEmail($message);

            return [
                'status' => 'success',
                'type' => 'email',
                'message' => 'Email notification sent',
                'data' => $message->toArray()
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Test send SMS notification
     */
    public function testSms($data = null)
    {
        try {
            $message = new Message([
                'order_id' => $data['order_id'] ?? 'TEST-002',
                'user_id' => $data['user_id'] ?? 'USER-002',
                'content' => $data['content'] ?? 'Test SMS notification',
                'timestamp' => date('c')
            ]);

            $this->notificationService->sendSms($message);

            return [
                'status' => 'success',
                'type' => 'sms',
                'message' => 'SMS notification sent',
                'data' => $message->toArray()
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Test send FCM notification
     */
    public function testFcm($data = null)
    {
        try {
            $message = new Message([
                'order_id' => $data['order_id'] ?? 'TEST-003',
                'user_id' => $data['user_id'] ?? 'USER-003',
                'content' => $data['content'] ?? 'Test FCM notification',
                'timestamp' => date('c')
            ]);

            $this->notificationService->sendFcm($message);

            return [
                'status' => 'success',
                'type' => 'fcm',
                'message' => 'FCM notification sent',
                'data' => $message->toArray()
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
