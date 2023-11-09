<?php

declare(strict_types=1);

use Shared\Infrastructure\DependencyInjection\ContainerFactory;
use Shared\Infrastructure\Slim\MicroserviceSlimInterface;

require __DIR__ . '/../vendor/autoload.php';

try {
    $container = ContainerFactory::buildContainer(__DIR__ . '/../config/settings.php');

    return $container->get(MicroserviceSlimInterface::class);
} catch (Exception $e) {
    throw new RuntimeException('Cant start');
}
