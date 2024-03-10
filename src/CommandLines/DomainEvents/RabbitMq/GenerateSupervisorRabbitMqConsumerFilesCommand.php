<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Slim\CommandLines\DomainEvents\RabbitMq;

use Shared\Domain\Bus\Event\DomainEventSubscriber;
use Shared\Domain\CommandLine\CommandLine;
use Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqQueueNameFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Lambdish\Phunctional\each;

final class GenerateSupervisorRabbitMqConsumerFilesCommand extends Command implements CommandLine
{
    private static string $defaultName = 'rabbitmq:generate-supervisor';

    public function __construct(
        private readonly ConfigurationRabbit $configurationRabbit,
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        each(
            $this->configCreator($this->configurationRabbit->cliPathFile()),
            $this->configurationRabbit->subscribers()
        );

        return 0;
    }

    private function configCreator(string $cliPathFile): callable
    {
        return function (DomainEventSubscriber $subscriber) use ($cliPathFile): void {
            $queueName = RabbitMqQueueNameFormatter::format($subscriber);
            $subscriberName = RabbitMqQueueNameFormatter::shortFormat($subscriber);

            $fileContent = str_replace(
                ['{subscriber_name}', '{queue_name}', '{cliPathFile}', '{processes}', '{events_to_process}', ],
                [
                    $subscriberName,
                    $queueName,
                    $cliPathFile,
                    $this->configurationRabbit->eventsToProcessAtTime(),
                    $this->configurationRabbit->numberOfProcessesPerSubscriber(),
                ],
                $this->template()
            );

            file_put_contents($this->fileName($subscriberName), $fileContent);
        };
    }

    private function template(): string
    {
        return <<<EOF
            [program:auth_{queue_name}]
            command      = {cliPathFile} auth:rabbitmq:consume {queue_name} {events_to_process}
            process_name = %(program_name)s_%(process_num)02d
            numprocs     = {processes}
            startsecs    = 1
            startretries = 10
            exitcodes    = 2
            stopwaitsecs = 300
            autostart    = true
            EOF;
    }

    private function fileName(string $queue): string
    {
        return sprintf('%s/%s.ini', $this->configurationRabbit->supervisordPath(), $queue);
    }
}
