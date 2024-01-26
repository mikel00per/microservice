<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Slim\Loggers;

use Exception;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Log\LoggerInterface;

final class LoggerFactory
{
    private array $handlers = [];
    private array $processors = [];

    public function __construct(
        private readonly string $path,
        private readonly int $level = 100,
        private readonly ?LoggerInterface $testLogger = null
    ) {}

    /**
     * @throws Exception
     */
    public function createLogger(string $name = null): LoggerInterface
    {
        if (isset($this->testLogger)) {
            return $this->testLogger;
        }

        $logger = new Logger($name ?? uniqid('logger', true));

        foreach ($this->processors as $processor) {
            $logger->pushProcessor($processor);
        }

        foreach ($this->handlers as $handler) {
            $logger->pushHandler($handler);
        }

        $this->processors = [];
        $this->handlers = [];

        return $logger;
    }

    public function addHandler(HandlerInterface $handler): self
    {
        $this->handlers[] = $handler;

        return $this;
    }

    public function addProcessor(UidProcessor $processor): self
    {
        $this->processors[] = $processor;

        return $this;
    }

    public function addFileHandler(string $filename, int $level = null): self
    {
        $filename = sprintf('%s/%s', $this->path, $filename);

        $rotatingFileHandler = new RotatingFileHandler($filename, 0, $level ?? $this->level, true, 0777);

        // The last "true" here tells monolog to remove empty []'s
        $rotatingFileHandler->setFormatter(new LineFormatter(null, null, false, true));

        $this->addHandler($rotatingFileHandler);

        return $this;
    }

    public function addConsoleHandler(int $level = null): self
    {
        /** @phpstan-ignore-next-line */
        $streamHandler = new StreamHandler('php://output', $level ?? $this->level);
        $streamHandler->setFormatter(new LineFormatter(null, null, false, true));

        $this->addHandler($streamHandler);

        return $this;
    }
}
