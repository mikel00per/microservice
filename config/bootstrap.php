<?php

declare(strict_types=1);

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Shared\Infrastructure\DependencyInjection\ContainerFactory;
use Shared\Infrastructure\Slim\MicroserviceSlimInterface;

require __DIR__ . '/../vendor/autoload.php';

try {
    return ContainerFactory::create(__DIR__ . '/../config/settings.php')
        ->get(MicroserviceSlimInterface::class);
} catch (NotFoundExceptionInterface|ContainerExceptionInterface|Exception $e) {
    throw new RuntimeException('Cant start');
}
