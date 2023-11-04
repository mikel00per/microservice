<?php

declare(strict_types=1);

use Infrastructure\Http\MicroserviceSlimInterface;
use Infrastructure\Http\Middlewares\DefaultHeadersMiddleware;
use Infrastructure\Http\Middlewares\SessionMiddleware;
use Slim\Middleware\ErrorMiddleware;

return static function (MicroserviceSlimInterface $microservice) {
    // Parse json, form data and xml
    $microservice->addBodyParsingMiddleware();
    // Add the Slim built-in routing middleware
    $microservice->addRoutingMiddleware();
    $microservice->add(SessionMiddleware::class);
    $microservice->add(DefaultHeadersMiddleware::class);
    $microservice->add(ErrorMiddleware::class);
};
