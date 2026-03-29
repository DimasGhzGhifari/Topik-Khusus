#!/bin/bash
# Start simple PHP API server for notification consumer testing
# Usage: ./run-api.sh

echo ""
echo "============================================"
echo "Notification Consumer - API Server"
echo "============================================"
echo ""
echo "Starting API server on http://localhost:8000"
echo "Press Ctrl+C to stop"
echo ""
echo "Available endpoints:"
echo "   GET  http://localhost:8000/health"
echo "   POST http://localhost:8000/test/email"
echo "   POST http://localhost:8000/test/sms"
echo "   POST http://localhost:8000/test/fcm"
echo ""
echo "For Postman commands, see: POSTMAN_COMMANDS.md"
echo ""

cd "$(dirname "$0")"
php -S localhost:8000 api-server.php
