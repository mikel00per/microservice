<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Slim;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Shared\Infrastructure\Settings\SettingsInterface;
use Slim\App;

final class MicroserviceSlim extends App implements MicroserviceSlimInterface
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct(
            $container->get(ResponseFactoryInterface ::class),
            $container
        );

        $settings = $container->get(SettingsInterface::class);

        if ($settings->get('router.cache.enabled')) {
            $this->getRouteCollector()->setCacheFile($settings->get('router.cache.path'));
        }
    }
}
