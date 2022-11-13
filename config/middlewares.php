<?php

declare(strict_types=1);

use Microservices\Http\MicroserviceSlimInterface;
use Microservices\Http\Middlewares\DefaultHeadersMiddleware;
use Microservices\Http\Middlewares\SessionMiddleware;
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
