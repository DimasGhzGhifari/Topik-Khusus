# Contoh Command GET/POST untuk Postman

## 🟢 GET - Health Check

```
GET http://localhost:8000/health
```

**Response:**
```json
{
  "status": "OK",
  "service": "Notification Consumer",
  "version": "1.0.0",
  "timestamp": "2026-03-29T10:00:00+00:00"
}
```

---

## 🔵 POST - Test Email Notification

```
POST http://localhost:8000/test/email
Content-Type: application/json

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

## 🔵 POST - Test SMS Notification

```
POST http://localhost:8000/test/sms
Content-Type: application/json

{
  "order_id": "ORDER-54321",
  "user_id": "USER-11111",
  "content": "Your package is on the way!"
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
    "timestamp": "2026-03-29T10:00:00+00:00"
  }
}
```

---

## 🔵 POST - Test FCM Notification

```
POST http://localhost:8000/test/fcm
Content-Type: application/json

{
  "order_id": "ORDER-99999",
  "user_id": "USER-22222",
  "content": "Push notification from FCM service"
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
    "content": "Push notification from FCM service",
    "timestamp": "2026-03-29T10:00:00+00:00"
  }
}
```

---

## 🚀 Quick Start dengan Postman

### 1. Buka Postman

### 2. Buat Request Baru
- Pilih method: GET atau POST
- Masukkan URL dari contoh di atas
- Untuk POST: Tab "Body" → pilih "raw" → "JSON"

### 3. Jalankan
- Klik **Send**
- Lihat response di bawah

### 4. Contoh Full Request untuk Email

**In Postman:**
```
[GET] dropdown ← klik
[POST] ← pilih POST

URL bar: http://localhost:8000/test/email

Headers tab:
  Key: Content-Type
  Value: application/json

Body tab:
  Raw - JSON
  {
    "order_id": "TEST-001",
    "user_id": "USER-001", 
    "content": "Test notification",
    "timestamp": "2026-03-29T10:00:00Z"
  }

[Send] button
```

---

## 📮 Curl Commands (if prefer terminal)

### GET Health
```bash
curl http://localhost:8000/health
```

### POST Email
```bash
curl -X POST http://localhost:8000/test/email \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "ORDER-123",
    "user_id": "USER-456",
    "content": "Test email"
  }'
```

### POST SMS
```bash
curl -X POST http://localhost:8000/test/sms \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "ORDER-789",
    "user_id": "USER-999",
    "content": "Test SMS"
  }'
```

### POST FCM  
```bash
curl -X POST http://localhost:8000/test/fcm \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "ORDER-321",
    "user_id": "USER-654",
    "content": "Test FCM"
  }'
```

---

## 🔗 Publisher API (Go)

Jika ingin publish message yang akan ke semua consumers:

```
POST http://localhost:8080/publish
Content-Type: application/json

{
  "order_id": "LIVE-001",
  "user_id": "LIVE-USER-001",
  "content": "Broadcast message to all consumers",
  "timestamp": "2026-03-29T10:00:00Z"
}
```

---

**Lihat POSTMAN_COMMANDS.md untuk dokumentasi lengkap!**
