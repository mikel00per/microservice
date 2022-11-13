<?php

namespace Microservices\Tests\Http;

use ContainerSettings\Settings;
use ContainerSettings\SettingsInterface;
use Exception;
use Microservices\Http\MicroserviceSlim;
use Microservices\Http\MicroserviceSlimInterface;
use Microservices\Tests\Utils\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Selective\TestTrait\Traits\HttpTestTrait;

class MicroserviceSlimTest extends TestCase
{
    use HttpTestTrait;

    private MicroserviceSlim $microservice;

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

    public function testApplicationHasBeenCreated(): void
    {
        $this->assertInstanceOf(MicroserviceSlim::class, $this->microservice);
    }

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
}
