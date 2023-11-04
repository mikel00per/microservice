<?php

declare(strict_types=1);

namespace Infrastructure\Http;

use ContainerSettings\SettingsInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;

class MicroserviceSlim extends App implements MicroserviceSlimInterface
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
