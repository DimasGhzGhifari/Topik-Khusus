    <?php

namespace App\Console\Commands;

use App\Repositories\RabbitMQRepository;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Email Consumer Command
 * @phpstan-ignore-next-line
 */
class EmailConsumerCommand extends Command
{
    protected $signature = 'consumer:email {--host=localhost} {--port=5672} {--user=guest} {--password=guest} {--vhost=/} {--exchange=notifications}';

    protected $description = 'Consume email messages from RabbitMQ';

    public function handle(): void
    {
        $host = $this->option('host');
        $port = $this->option('port');
        $user = $this->option('user');
        $password = $this->option('password');
        $vhost = $this->option('vhost');
        $exchangeName = $this->option('exchange');

        try {
            $connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
            $repository = new RabbitMQRepository($connection, $exchangeName);

            $this->info('[EMAIL] Starting Email Consumer...');
            $repository->consumeEmailMessages('EMAIL');

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
