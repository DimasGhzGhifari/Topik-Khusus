<?php

namespace App\Http\Controllers;

use App\Services\RabbitMQService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function publish(Request $request, RabbitMQService $rabbitMQ): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|string',
            'user_id' => 'required|string',
            'content' => 'required|string',
            'timestamp' => 'required|string',
        ]);

        try {
            $rabbitMQ->publish($validated);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to publish message',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Message published successfully',
        ], 200);
    }
}
