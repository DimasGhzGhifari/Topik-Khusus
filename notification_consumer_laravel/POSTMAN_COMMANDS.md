# Postman Commands - Notification System

## 🚀 Test Setup

### Start Services

**Terminal 1 - API Server** (untuk testing consumer)
```bash
php api-server.php
```
Server akan berjalan di: `http://localhost:8000`

**Terminal 2 - Publisher** (untuk mengirim message)
```bash
cd notification_publisher
go run main.go
```
Publisher API di: `http://localhost:8080`

**Terminal 3 - Email Consumer** (mendengarkan message)
```bash
cd notification_consumer_laravel
php scripts/email-consumer.php
```

**Terminal 4 - SMS Consumer**
```bash
php scripts/sms-consumer.php
```

**Terminal 5 - FCM Consumer**
```bash
php scripts/fcm-consumer.php
```

---

## 📮 Postman Commands

### 1. Health Check - Consumer API

**Method:** GET  
**URL:** 
```
http://localhost:8000/health
```

**Result:**
```json
{
  "status": "OK",
  "service": "Notification Consumer",
  "version": "1.0.0",
  "timestamp": "2026-03-29T10:00:00+00:00"
}
```

---

### 2. Test Email Notification (Consumer)

**Method:** POST  
**URL:**
```
http://localhost:8000/test/email
```

**Headers:**
```
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "order_id": "ORDER-12345",
  "user_id": "USER-67890",
  "content": "Your order has been confirmed!",
  "timestamp": "2026-03-29T10:00:00Z"
}
```

**Response:**
```json
{
  "status": "success",
  "type": "email",
  "message": "Email notification sent",
  "data": {
    "order_id": "ORDER-12345",
    "user_id": "USER-67890",
    "content": "Your order has been confirmed!",
    "timestamp": "2026-03-29T10:00:00Z"
  }
}
```

---

### 3. Test SMS Notification (Consumer)

**Method:** POST  
**URL:**
```
http://localhost:8000/test/sms
```

**Headers:**
```
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "order_id": "ORDER-54321",
  "user_id": "USER-11111",
  "content": "Your package is on the way!",
  "timestamp": "2026-03-29T10:00:00Z"
}
```

**Response:**
```json
{
  "status": "success",
  "type": "sms",
  "message": "SMS notification sent",
  "data": {
    "order_id": "ORDER-54321",
    "user_id": "USER-11111",
    "content": "Your package is on the way!",
    "timestamp": "2026-03-29T10:00:00Z"
  }
}
```

---

### 4. Test FCM Notification (Consumer)

**Method:** POST  
**URL:**
```
http://localhost:8000/test/fcm
```

**Headers:**
```
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "order_id": "ORDER-99999",
  "user_id": "USER-22222",
  "content": "Push notification test from FCM",
  "timestamp": "2026-03-29T10:00:00Z"
}
```

**Response:**
```json
{
  "status": "success",
  "type": "fcm",
  "message": "FCM notification sent",
  "data": {
    "order_id": "ORDER-99999",
    "user_id": "USER-22222",
    "content": "Push notification test from FCM",
    "timestamp": "2026-03-29T10:00:00Z"
  }
}
```

---

### 5. Publish Message (Publisher API)

**Method:** POST  
**URL:**
```
http://localhost:8080/publish
```

**Headers:**
```
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "order_id": "ORDER-LIVE-001",
  "user_id": "USER-LIVE-001",
  "content": "New order received - live message from publisher",
  "timestamp": "2026-03-29T10:00:00Z"
}
```

**Response:**
```json
{
  "code": 200,
  "message": "Message published successfully"
}
```

> **Info:** Message ini akan dikirim ke semua consumers (email, sms, fcm) yang sedang listening!

---

## 🔄 Full Testing Flow

### Scenario: Publish Message → Consumers Receive

1. **Start all services** (5 terminals seperti di atas)

2. **Send message dari Publisher:**
   ```
   POST http://localhost:8080/publish
   ```
   dengan body yang valid

3. **Check consumer terminals:**
   - Email consumer akan print: `[EMAIL] Received message: {...}`
   - SMS consumer akan print: `[SMS] Received message: {...}`
   - FCM consumer akan print: `[FCM] Received message: {...}`

---

## 📋 Curl Commands (Alternative)

### Health Check
```bash
curl -X GET http://localhost:8000/health
```

### Test Email via Consumer API
```bash
curl -X POST http://localhost:8000/test/email \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "ORDER-12345",
    "user_id": "USER-67890",
    "content": "Test email",
    "timestamp": "2026-03-29T10:00:00Z"
  }'
```

### Test SMS via Consumer API
```bash
curl -X POST http://localhost:8000/test/sms \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "ORDER-54321",
    "user_id": "USER-11111",
    "content": "Test SMS",
    "timestamp": "2026-03-29T10:00:00Z"
  }'
```

### Test FCM via Consumer API
```bash
curl -X POST http://localhost:8000/test/fcm \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "ORDER-99999",
    "user_id": "USER-22222",
    "content": "Test FCM",
    "timestamp": "2026-03-29T10:00:00Z"
  }'
```

### Publish Message via Publisher API
```bash
curl -X POST http://localhost:8080/publish \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "ORDER-LIVE-001",
    "user_id": "USER-LIVE-001",
    "content": "Message from publisher",
    "timestamp": "2026-03-29T10:00:00Z"
  }'
```

---

## 🔧 Postman Collection JSON

Bisa import ke Postman langsung:

```json
{
  "info": {
    "name": "Notification System",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Consumer API",
      "item": [
        {
          "name": "Health Check",
          "request": {
            "method": "GET",
            "url": "http://localhost:8000/health"
          }
        },
        {
          "name": "Test Email",
          "request": {
            "method": "POST",
            "header": [{"key": "Content-Type", "value": "application/json"}],
            "url": "http://localhost:8000/test/email",
            "body": {
              "mode": "raw",
              "raw": "{\"order_id\": \"ORDER-12345\", \"user_id\": \"USER-67890\", \"content\": \"Test email\", \"timestamp\": \"2026-03-29T10:00:00Z\"}"
            }
          }
        },
        {
          "name": "Test SMS",
          "request": {
            "method": "POST",
            "header": [{"key": "Content-Type", "value": "application/json"}],
            "url": "http://localhost:8000/test/sms",
            "body": {
              "mode": "raw",
              "raw": "{\"order_id\": \"ORDER-54321\", \"user_id\": \"USER-11111\", \"content\": \"Test SMS\", \"timestamp\": \"2026-03-29T10:00:00Z\"}"
            }
          }
        },
        {
          "name": "Test FCM",
          "request": {
            "method": "POST",
            "header": [{"key": "Content-Type", "value": "application/json"}],
            "url": "http://localhost:8000/test/fcm",
            "body": {
              "mode": "raw",
              "raw": "{\"order_id\": \"ORDER-99999\", \"user_id\": \"USER-22222\", \"content\": \"Test FCM\", \"timestamp\": \"2026-03-29T10:00:00Z\"}"
            }
          }
        }
      ]
    },
    {
      "name": "Publisher API",
      "item": [
        {
          "name": "Publish Message",
          "request": {
            "method": "POST",
            "header": [{"key": "Content-Type", "value": "application/json"}],
            "url": "http://localhost:8080/publish",
            "body": {
              "mode": "raw",
              "raw": "{\"order_id\": \"ORDER-LIVE-001\", \"user_id\": \"USER-LIVE-001\", \"content\": \"Message from publisher\", \"timestamp\": \"2026-03-29T10:00:00Z\"}"
            }
          }
        }
      ]
    }
  ]
}
```

---

## 📊 Expected Behavior

| Action | Consumer Output | Expected Result |
|--------|-----------------|-----------------|
| `GET /health` | - | HTTP 200 + status OK |
| `POST /test/email` | `[EMAIL] Sending email...` | Email notif logged |
| `POST /test/sms` | `[SMS] Sending SMS...` | SMS notif logged |
| `POST /test/fcm` | `[FCM] Sending FCM...` | FCM notif logged |
| `POST /publish` | All 3 consumers receive | All consumers print messages |

---

**Happy Testing!** 🎉
