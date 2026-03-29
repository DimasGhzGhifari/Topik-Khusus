CONVERSION GUIDE: Go to Laravel (PHP)
=====================================

## Perubahan Struktur Kode

### 1. Model/Entity
**Go (entity.go):**
```go
type Message struct {
    OrderID   string `json:"order_id"`
    UserID    string `json:"user_id"`
    Content   string `json:"content"`
    Timestamp string `json:"timestamp"`
}
```

**Laravel (Message.php):**
```php
class Message extends Model
{
    protected $fillable = [
        'order_id', 'user_id', 'content', 'timestamp'
    ];
}
```

---

### 2. Notification Services
**Go (email.go, sms.go, fcm.go):**
```go
func SendEmail(message entity.Message) {
    fmt.Println("sending email ...", message.Content)
}
```

**Laravel (NotificationService.php):**
```php
public function sendEmail(Message $message): void
{
    echo "[EMAIL] Sending email notification...\n";
    echo "[EMAIL] Content: {$message->content}\n";
}
```

---

### 3. Repository/RabbitMQ Integration
**Go (rabbitmq_repo.go):**
- Menggunakan `github.com/rabbitmq/amqp091-go`
- Channel operations: `ch.ExchangeDeclare()`, `ch.QueueDeclare()`, `ch.Consume()`

**Laravel (RabbitMQRepository.php):**
- Menggunakan `php-amqplib/php-amqplib`
- Channel operations: `$channel->exchange_declare()`, `$channel->queue_declare()`, `$channel->basic_consume()`

---

### 4. Console Commands / Entry Points
**Go (cmd/email/main.go, etc.):**
```go
func main() {
    rmqConn, _ := rabbitmq.NewRabbitMQConnection(cfg.RabbitMQURL)
    repo := repository.NewRabbitMQRepository(rmqConn, cfg.ExchangeName)
    useCase.ConsumeMessagesEmail(context.Background(), serviceName)
}
```

**Laravel (EmailConsumerCommand.php):**
```php
class EmailConsumerCommand extends Command {
    public function handle(): void {
        $connection = new AMQPStreamConnection(...);
        $repository = new RabbitMQRepository($connection, $exchangeName);
        $repository->consumeEmailMessages('EMAIL');
    }
}
```

---

### 5. Configuration Management

**Go (config/config.go):**
- Menggunakan environment variables
- Struct-based configuration

**Laravel (config/rabbitmq.php):**
- File-based configuration
- Access via `config('rabbitmq.rabbitmq.host')`

---

## Fitur Laravel yang Digunakan

1. **Artisan Console Commands** - Menggantikan Go's main.go entry points
2. **Service Container** - Dependency injection untuk services
3. **Model** - Eloquent ORM untuk Message entity
4. **Configuration System** - Centralized config management

---

## Perbedaan Utama

| Aspek | Go | Laravel |
|-------|----|----|
| Entry Point | `main.go` dengan `func main()` | Artisan Commands |
| Package Manager | `go mod` | `composer` |
| Concurrency | Goroutines | Tidak perlu, single-threaded consumer |
| Error Handling | `if err != nil` | Try-catch exceptions |
| Logging | `log` package | Laravel Log facade |
| Configuration | Environment variables | `.env` file + config files |

---

## Menjalankan Consumers

### Go:
```bash
go run cmd/email/main.go
go run cmd/sms/main.go
go run cmd/fcm/main.go
```

### Laravel:
```bash
php artisan consumer:email
php artisan consumer:sms
php artisan consumer:fcm
```

---

## Dependensi PHP yang Diperlukan

```json
{
    "require": {
        "php": "^8.1",
        "laravel/framework": "^11.0",
        "php-amqplib/php-amqplib": "^11.0"
    }
}
```

Install dengan: `composer install`

---

## Migrasi Selesai ✓

Semua fungsionalitas Go telah dikonversi ke Laravel dengan:
- ✓ RabbitMQ Integration
- ✓ Three Consumer Commands (Email, SMS, FCM)
- ✓ Message Model & Serialization
- ✓ Notification Services
- ✓ Configuration Management
- ✓ Error Handling

Kode Laravel lebih terintegrasi dengan framework dan lebih mudah diperluas!
