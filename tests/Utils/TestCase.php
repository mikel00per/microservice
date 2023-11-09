<?php

namespace Shared\Infrastructure\Slim\Tests\Utils;

use Exception;
use PHPUnit\Framework\TestCase as UnitTestCase;
use Selective\TestTrait\Traits\ContainerTestTrait;
use Selective\TestTrait\Traits\MockTestTrait;
use Shared\Infrastructure\DependencyInjection\ContainerFactory;

class TestCase extends UnitTestCase
{
    use ContainerTestTrait;
    use MockTestTrait;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $container = ContainerFactory::buildContainer(__DIR__ . '/../../config/settings.php');
        $this->setUpContainer($container);
    }
}
