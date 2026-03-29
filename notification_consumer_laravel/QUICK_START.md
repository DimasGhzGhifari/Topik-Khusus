QUICK START GUIDE
=================

## 1. Setup Awal

```bash
cd notification_consumer_laravel
```

### Option A: Jika sudah punya Laravel environment
- Copy struktur folder ke dalam project Laravel yang ada
- Edit `composer.json` untuk tambahkan dependency php-amqplib

### Option B: Setup standalone
```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Update .env dengan kredensial RabbitMQ Anda jika diperlukan
```

---

## 2. Install Dependencies PHP AMQP

```bash
composer require php-amqplib/php-amqplib
```

---

## 3. Setup RabbitMQ

Pastikan RabbitMQ server berjalan:

**Docker:**
```bash
docker run -d --name rabbitmq -p 5672:5672 -p 15672:15672 rabbitmq:3-management
```

**Atau akses dengan kredensial default:**
- Host: localhost
- Port: 5672
- Username: guest
- Password: guest

---

## 4. Jalankan Consumer

**Terminal 1 - Email Consumer:**
```bash
php artisan consumer:email
```

**Terminal 2 - SMS Consumer:**
```bash
php artisan consumer:sms
```

**Terminal 3 - FCM Consumer:**
```bash
php artisan consumer:fcm
```

---

## 5. Test dengan Publisher

Gunakan aplikasi `notification_publisher` untuk mengirim message ke RabbitMQ.

Consumer akan menerima dan memproses message:

```
[EMAIL] Listening for messages...
[EMAIL] Received message: {"order_id":"12345",...}
[EMAIL] Sending email notification...
[EMAIL] Content: New order received
[EMAIL] To User: 67890 | Order: 12345
```

---

## 6. Struktur File

```
notification_consumer_laravel/
├── app/
│   ├── Console/Commands/
│   │   ├── EmailConsumerCommand.php     # Run: php artisan consumer:email
│   │   ├── SmsConsumerCommand.php       # Run: php artisan consumer:sms
│   │   └── FcmConsumerCommand.php       # Run: php artisan consumer:fcm
│   ├── Models/
│   │   └── Message.php                  # Message entity model
│   ├── Repositories/
│   │   └── RabbitMQRepository.php       # RabbitMQ integration
│   └── Services/
│       ├── NotificationService.php      # Notification logic
│       └── RabbitMQService.php          # RabbitMQ connection handling
├── config/
│   └── rabbitmq.php                     # RabbitMQ configuration
├── .env                                 # Environment variables
├── .env.example                         # Example environment
├── README.md                            # Full documentation
├── CONVERSION_GUIDE.md                  # Go to Laravel conversion guide
├── QUICK_START.md                       # This file
└── composer.json                        # PHP dependencies
```

---

## 7. Customization

### Mengubah Host/Port RabbitMQ
Edit `.env`:
```env
RABBITMQ_HOST=your-host
RABBITMQ_PORT=5672
RABBITMQ_USER=your-user
RABBITMQ_PASSWORD=your-password
RABBITMQ_VHOST=/
```

### Mengubah Exchange Name
Edit `.env`:
```env
RABBITMQ_EXCHANGE_NAME=your-exchange-name
```

---

## 8. Tips Debugging

### Lihat RabbitMQ Admin Panel
```
http://localhost:15672
# Username: guest
# Password: guest
```

### Check message di queue
```php
php artisan tinker
>>> \App\Services\RabbitMQService::getConnection()
```

---

## 9. Production Considerations

- [ ] Gunakan Supervisor untuk auto-restart consumer
- [ ] Setup proper error logging dengan Laravel Log
- [ ] Implement actual email/SMS/FCM sending dalam service
- [ ] Add database logging untuk message history
- [ ] Setup health check endpoint
- [ ] Configure retry policy untuk failed messages

---

**Selesai! Consumer siap digunakan dengan Laravel.** ✓
