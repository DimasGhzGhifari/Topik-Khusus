<?php

namespace App\Models;

/**
 * Simple Message class - no database ORM needed
 * This is just a data container for notification messages
 */
class Message
{
    public string $order_id;
    public string $user_id;
    public string $content;
    public string $timestamp;

    public function __construct(array $data = [])
    {
        $this->order_id = $data['order_id'] ?? '';
        $this->user_id = $data['user_id'] ?? '';
        $this->content = $data['content'] ?? '';
        $this->timestamp = $data['timestamp'] ?? date('c');
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'content' => $this->content,
            'timestamp' => $this->timestamp
        ];
    }

    /**
     * Convert to JSON
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Create from JSON
     */
    public static function fromJson(string $json): self
    {
        return new self(json_decode($json, true));
    }
}
