<?php

namespace App\Services;

use App\Models\Message;

class NotificationService
{
    /**
     * Send Email Notification
     *
     * @param Message $message
     * @return void
     */
    public function sendEmail(Message $message): void
    {
        echo "[EMAIL] Sending email notification...\n";
        echo "[EMAIL] Content: {$message->content}\n";
        echo "[EMAIL] To User: {$message->user_id} | Order: {$message->order_id}\n";
        // TODO: Implement actual email sending logic
    }

    /**
     * Send SMS Notification
     *
     * @param Message $message
     * @return void
     */
    public function sendSms(Message $message): void
    {
        echo "[SMS] Sending SMS notification...\n";
        echo "[SMS] Content: {$message->content}\n";
        echo "[SMS] To User: {$message->user_id} | Order: {$message->order_id}\n";
        // TODO: Implement actual SMS sending logic
    }

    /**
     * Send FCM (Firebase Cloud Messaging) Notification
     *
     * @param Message $message
     * @return void
     */
    public function sendFcm(Message $message): void
    {
        echo "[FCM] Sending FCM notification...\n";
        echo "[FCM] Content: {$message->content}\n";
        echo "[FCM] To User: {$message->user_id} | Order: {$message->order_id}\n";
        // TODO: Implement actual FCM sending logic
    }
}
