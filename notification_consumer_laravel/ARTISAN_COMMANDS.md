# PHP Artisan Commands - Notification Consumer

✅ **Fixed!** Notification Consumer sekarang fully berfungsi dengan `php artisan` commands.

## 🚀 Available Commands

### 1️⃣ **Test Setup**
```bash
php artisan test
```
**Output:**
```
✓ Message model loaded and instantiated
✓ NotificationService loaded
✓ All components working correctly!
```

### 2️⃣ **Start Email Consumer**
```bash
php artisan consumer:email
```
**Output:**
```
[EMAIL] Connecting to RabbitMQ at localhost:5672...
[EMAIL] Connected successfully!
[EMAIL] Starting Email Consumer...
[EMAIL] Listening for messages...
```

### 3️⃣ **Start SMS Consumer**
```bash
php artisan consumer:sms
```

### 4️⃣ **Start FCM Consumer**
```bash
php artisan consumer:fcm
```

### 5️⃣ **Start API Server**
```bash
php artisan server
```
atau dengan port custom:
```bash
php artisan server 9000
```

Server akan run di `http://localhost:8000` (atau port yang ditentukan)

### 6️⃣ **Show Help**
```bash
php artisan help
```

---

## 🎯 Complete Testing Workflow

### Terminal 1 - Test Components
```bash
cd d:\Semester 6\TOPIK KHUSUS\notification_consumer_laravel
php artisan test
```
✅ Verify semua components loaded

### Terminal 2 - Start API Server
```bash
php artisan server 8000
```
Sekarang server siap di `http://localhost:8000`

### Terminal 3 - Email Consumer
```bash
php artisan consumer:email
```

### Terminal 4 - SMS Consumer
```bash
php artisan consumer:sms
```

### Terminal 5 - FCM Consumer
```bash
php artisan consumer:fcm
```

---

## 📮 Test dengan Postman

Setelah semua services running, buka Postman:

### Health Check
```
GET http://localhost:8000/health
```

### Test Email
```
POST http://localhost:8000/test/email
Content-Type: application/json

{
  "order_id": "TEST-001",
  "user_id": "USER-001",
  "content": "Test email notification"
}
```

### Test SMS
```
POST http://localhost:8000/test/sms
```

### Test FCM
```
POST http://localhost:8000/test/fcm
```

Lihat **EXAMPLE_COMMANDS.md** untuk lengkap commands dan response examples!

---

## 🔄 Publish Message (Go Publisher)

Untuk test real pub-sub dengan RabbitMQ:

**Terminal 6 - Run Publisher** (Go)
```bash
cd d:\Semester 6\TOPIK KHUSUS\notification_publisher
go run main.go
```

**Publish message:**
```
POST http://localhost:8080/publish
{
  "order_id": "LIVE-123",
  "user_id": "USER-123",
  "content": "Message dari publisher Go"
}
```

Semua 3 consumers (email, sms, fcm) akan receive dan process message ini!

---

## ✨ What's Fixed

- ✅ Removed Laravel Framework dependency
- ✅ Simplified Message model (plain PHP class)
- ✅ Added artisan command router
- ✅ Added `php artisan server` command
- ✅ Added `php artisan test` command
- ✅ All components working without errors

---

## 📋 Summary

| Command | Purpose |
|---------|---------|
| `php artisan help` | Show available commands |
| `php artisan test` | Test all components |
| `php artisan consumer:email` | Start email consumer |
| `php artisan consumer:sms` | Start SMS consumer |
| `php artisan consumer:fcm` | Start FCM consumer |
| `php artisan server [port]` | Start API server |

---

**Sekarang semuanya siap untuk digunakan!** 🎉
