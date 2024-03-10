<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Slim\CommandLines\DomainEvents\RabbitMq;

use AMQPChannelException;
use AMQPConnectionException;
use AMQPExchangeException;
use Exception;
use Psr\Log\LoggerInterface;
use Shared\Domain\CommandLine\CommandLine;
use Shared\Infrastructure\Bus\Event\DomainEventSubscriberLocator;
use Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqDomainEventsConsumer;
use Shared\Infrastructure\Doctrine\DatabaseConnections;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Lambdish\Phunctional\repeat;

final class ConsumeRabbitMqDomainEventsCommand extends Command implements CommandLine
{
    private static string $defaultName = 'rabbitmq:consume';

    public function __construct(
        private readonly RabbitMqDomainEventsConsumer $rabbitMqDomainEventsConsumer,
        private readonly DatabaseConnections $connections,
        private readonly DomainEventSubscriberLocator $domainEventSubscriberLocator,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('queue', InputArgument::REQUIRED, 'Queue name')
            ->addArgument('quantity', InputArgument::REQUIRED, 'Quantity of events to process');
    }
    /**
     * @throws AMQPExchangeException|AMQPChannelException|AMQPConnectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $queueName = $input->getArgument('queue');
        $eventsToProcess = (int) $input->getArgument('quantity');


        repeat($this->consumer($queueName), $eventsToProcess);

        return 0;
    }

    private function consumer(string $queueName): callable
    {
        return function () use ($queueName): void {
            $subscriber = $this->domainEventSubscriberLocator->withRabbitMqQueueNamed($queueName);

            try {
                $this->rabbitMqDomainEventsConsumer->consume($subscriber, $queueName);
            } catch (Exception $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());
            }

            $this->connections->clear();
        };
    }
}
