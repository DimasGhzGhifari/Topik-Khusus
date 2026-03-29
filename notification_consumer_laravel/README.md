# 📬 Notification Consumer - Laravel Version

> Converted from Go to **Laravel (PHP)** | Fully functional notification consumer service

## ✨ Features

- ✅ **Email Consumer** - Listen and process email notifications
- ✅ **SMS Consumer** - Listen and process SMS notifications  
- ✅ **FCM Consumer** - Listen and process push notifications
- ✅ **RabbitMQ Integration** - Real-time message processing
- ✅ **API Server** - Test endpoints with Postman
- ✅ **Artisan CLI** - Simple commands to run consumers
- ✅ **No Framework Overhead** - Minimal dependencies, pure PHP

---

## 🚀 Quick Start

### 1. Install Dependencies
```bash
cd notification_consumer_laravel
composer install --ignore-platform-req=ext-sockets
```

### 2. Test Components
```bash
php artisan test
```

### 3. Start API Server
```bash
php artisan server 8000
```

### 4. Start Consumers
```bash
# Terminal 1
php artisan consumer:email

# Terminal 2
php artisan consumer:sms

# Terminal 3
php artisan consumer:fcm
```

### 5. Test with Postman
```
GET http://localhost:8000/health
POST http://localhost:8000/test/email
POST http://localhost:8000/test/sms
POST http://localhost:8000/test/fcm
```

See **EXAMPLE_COMMANDS.md** for complete examples!

---

## 📚 Documentation

- [SETUP.md](SETUP.md) - Detailed setup instructions
- [CONVERSION_GUIDE.md](CONVERSION_GUIDE.md) - Go to Laravel conversion details
- [ARTISAN_COMMANDS.md](ARTISAN_COMMANDS.md) - All available commands
- [POSTMAN_COMMANDS.md](POSTMAN_COMMANDS.md) - Testing endpoints with Postman
- [IDE_WARNING_EXPLANATION.md](IDE_WARNING_EXPLANATION.md) - About Pylance/VS Code warnings

---

## ⚠️ About "Red Files" in VS Code

You might see files highlighted with errors like `Undefined type 'PhpAmqpLib\Connection\AMQPStreamConnection'`.

**This is NOT a problem!** The code runs perfectly. This is a Pylance indexing issue with vendor packages.

See [IDE_WARNING_EXPLANATION.md](IDE_WARNING_EXPLANATION.md) for:
- ✅ Proof that code works (test output)
- ✅ Why this happens
- ✅ How to fix it (4 solutions)
