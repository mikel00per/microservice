<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Slim\Controllers;

use Shared\Domain\Bus\Command\Command;
use Shared\Domain\Bus\Command\CommandBus;
use Shared\Domain\Bus\Query\Query;
use Shared\Domain\Bus\Query\QueryBus;
use Shared\Domain\Bus\Query\Response;

abstract readonly class ApiController
{
    public function __construct(
        private QueryBus $queryBus,
        private CommandBus $commandBus,
    ) {}

    protected function ask(Query $query): ?Response
    {
        return $this->queryBus->ask($query);
    }

    protected function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
