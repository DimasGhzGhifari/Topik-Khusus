<?php

namespace App\Console\Commands;

use App\Repositories\RabbitMQRepository;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * SMS Consumer Command
 * @phpstan-ignore-next-line
 */
class SmsConsumerCommand extends Command
{
    protected $signature = 'consumer:sms {--host=localhost} {--port=5672} {--user=guest} {--password=guest} {--vhost=/} {--exchange=notifications}';

    protected $description = 'Consume SMS messages from RabbitMQ';

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

            $this->info('[SMS] Starting SMS Consumer...');
            $repository->consumeSmsMessages('SMS');

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
