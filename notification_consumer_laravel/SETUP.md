# Notification Consumer - Laravel Version

## 📋 Quick Setup

### 1. Prerequisites
- PHP 8.0+ 
- Composer
- RabbitMQ server (Docker atau local installation)

### 2. Installation

```bash
cd notification_consumer_laravel

# Install dependencies
composer install --ignore-platform-req=ext-sockets

# Copy and setup environment
cp .env.example .env
```

### 3. Configure RabbitMQ

Edit `.env` file:
```env
RABBITMQ_HOST=localhost
RABBITMQ_PORT=5672
RABBITMQ_USER=guest
RABBITMQ_PASSWORD=guest
RABBITMQ_VHOST=/
RABBITMQ_EXCHANGE_NAME=notifications
```

### 4. Test Setup

```bash
php scripts/test-setup.php
```

Expected output:
```
=== Notification Consumer Setup Test ===

✓ PHP Version: 8.x.x
✓ Vendor directory exists
✓ php-amqplib loaded successfully
✓ App\Models\Message loaded
✓ App\Services\NotificationService loaded
✓ App\Repositories\RabbitMQRepository loaded
✓ .env file exists
  - RABBITMQ_HOST: localhost
  - RABBITMQ_PORT: 5672
  - RABBITMQ_USER: guest
  - RABBITMQ_EXCHANGE_NAME: notifications

✓ Attempting RabbitMQ connection test...
✓ Connected to RabbitMQ at localhost:5672

=== Setup Test Complete ===
```

## 🚀 Running Consumers

Open 3 separate terminals:

### Terminal 1 - Email Consumer
```bash
php scripts/email-consumer.php
```

Output:
```
[EMAIL] Connecting to RabbitMQ at localhost:5672...
[EMAIL] Connected successfully!
[EMAIL] Starting Email Consumer...
[EMAIL] Listening for messages...
```

### Terminal 2 - SMS Consumer
```bash
php scripts/sms-consumer.php
```

### Terminal 3 - FCM Consumer
```bash
php scripts/fcm-consumer.php
```

## 📝 Project Structure

```
├── app/
│   ├── Console/
│   │   └── Commands/          # Artisan console commands (optional)
│   ├── Models/
│   │   └── Message.php         # Message model
│   ├── Repositories/
│   │   └── RabbitMQRepository.php  # RabbitMQ consumer logic
│   └── Services/
│       ├── NotificationService.php # Email/SMS/FCM sending
│       └── RabbitMQService.php     # RabbitMQ connection helper
├── scripts/
│   ├── email-consumer.php      # Email consumer script
│   ├── sms-consumer.php        # SMS consumer script
│   ├── fcm-consumer.php        # FCM consumer script
│   └── test-setup.php          # Setup verification
├── config/
│   └── rabbitmq.php            # RabbitMQ configuration
└── .env                        # Environment variables
```

## 🛠️ Setup RabbitMQ

### Option 1: Docker (Recommended)
```bash
docker run -d \
  --name rabbitmq \
  -p 5672:5672 \
  -p 15672:15672 \
  rabbitmq:3-management
```

Access admin panel: http://localhost:15672
- Username: guest
- Password: guest

### Option 2: Local Installation
- Windows: Download from https://www.rabbitmq.com/download.html
- Mac: `brew install rabbitmq`
- Linux: `sudo apt-get install rabbitmq-server`

## 📊 Testing with notification_publisher

1. Start all 3 consumers (in separate terminals)
2. Run notification_publisher to send messages
3. Each consumer will receive and process the message

## ⚠️ Troubleshooting

### "Could not open input file: artisan"
- This is expected. Use `php scripts/*.php` instead of `php artisan` commands

### "Connection refused" (RabbitMQ)
- Make sure RabbitMQ is running
- Check RABBITMQ_HOST and RABBITMQ_PORT in .env
- Use Docker: `docker run -d -p 5672:5672 rabbitmq:3`

### "sockets extension missing"
- Already handled in composer install with `--ignore-platform-req=ext-sockets`
- If needed later, enable it in `C:\xampp\php\php.ini`

### "No matching packages found"
- Run: `composer install --ignore-platform-req=ext-sockets`

## 🔧 Extending for Production

1. **Email Sending**: Update `App\Services\NotificationService::sendEmail()`
   - Integrate Laravel Mail or Sendgrid/Mailgun API

2. **SMS Sending**: Update `App\Services\NotificationService::sendSms()`
   - Integrate Twilio or Nexmo

3. **FCM Notifications**: Update `App\Services\NotificationService::sendFcm()`
   - Integrate Firebase Admin SDK

4. **Logging & Monitoring**: Add database logging for message history

5. **Process Management**: Use Supervisor to auto-restart consumers if they crash

---

**Version:** Laravel-converted from Go
**Last Updated:** March 2026
