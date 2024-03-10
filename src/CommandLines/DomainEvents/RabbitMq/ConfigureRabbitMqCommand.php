<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Slim\CommandLines\DomainEvents\RabbitMq;

use AMQPChannelException;
use AMQPConnectionException;
use AMQPExchangeException;
use Shared\Domain\CommandLine\CommandLine;
use Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqConfigurer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ConfigureRabbitMqCommand extends Command implements CommandLine
{
    private static string $defaultName = 'rabbitmq:configure';

    public function __construct(
        private readonly RabbitMqConfigurer $configurer,
        private readonly ConfigurationRabbit $configurationRabbit
    ) {
        parent::__construct(self::$defaultName);
    }

    /**
     * @throws AMQPExchangeException|AMQPChannelException|AMQPConnectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->configurer->configure(
            $this->configurationRabbit->exchangeName(),
            ...$this->configurationRabbit->subscribers()
        );

        return 0;
    }
}
