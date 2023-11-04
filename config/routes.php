<?php

declare(strict_types=1);

use Infrastructure\Http\MicroserviceSlimInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

return static function (MicroserviceSlimInterface $microservice) {
    $microservice->group('/api', function (RouteCollectorProxy $microservice) {
        $microservice->get('', function (Request $request, Response $response) {
            $response->getBody()->write('Ok');
            return $response;
        });
    });
};
