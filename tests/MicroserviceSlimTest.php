<?php

namespace Shared\Infrastructure\Slim\Tests;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Selective\TestTrait\Traits\HttpTestTrait;
use Shared\Infrastructure\Settings\Settings;
use Shared\Infrastructure\Settings\SettingsInterface;
use Shared\Infrastructure\Slim\MicroserviceSlim;
use Shared\Infrastructure\Slim\MicroserviceSlimInterface;
use Shared\Infrastructure\Slim\Tests\Utils\TestCase;

final class MicroserviceSlimTest extends TestCase
{
    use HttpTestTrait;

    private MicroserviceSlim $microservice;

    public function testApplicationHasBeenCreated(): void
    {
        $this->assertInstanceOf(MicroserviceSlim::class, $this->microservice);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetSettings(): void
    {
        $expected = Settings::class;
        $actual = $this->microservice->getContainer()->get(SettingsInterface::class);

        $this->assertInstanceOf($expected, $actual);
    }

    public function testHandleRequest(): void
    {
        $request = $this->createRequest('GET', '/api');
        $response = $this->microservice->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->microservice = $this->container->get(MicroserviceSlimInterface::class);
    }
}
