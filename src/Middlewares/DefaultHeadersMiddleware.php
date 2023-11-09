<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Slim\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

final readonly class DefaultHeadersMiddleware implements Middleware
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        return $handler->handle($request)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Connection', 'close')
            ->withProtocolVersion('1.0');
    }
}
