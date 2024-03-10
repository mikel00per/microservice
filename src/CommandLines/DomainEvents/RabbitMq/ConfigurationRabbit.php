<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Slim\CommandLines\DomainEvents\RabbitMq;

final readonly class ConfigurationRabbit
{
    public function __construct(
        private string $exchangeName,
        private string $supervisorPath,
        private string $eventsToProcessAtTime,
        private string $numberOfProcessesPerSubscriber,
        private string $cliPathFile,
        private array $subscribers,
    ) {}

    public function exchangeName(): string
    {
        return $this->exchangeName;
    }

    public function cliPathFile(): string
    {
        return $this->cliPathFile;
    }

    public function subscribers(): array
    {
        return $this->subscribers;
    }

    public function supervisordPath(): string
    {
        return $this->supervisorPath;
    }

    public function eventsToProcessAtTime(): string
    {
        return $this->eventsToProcessAtTime;
    }

    public function numberOfProcessesPerSubscriber(): string
    {
        return $this->numberOfProcessesPerSubscriber;
    }
}
