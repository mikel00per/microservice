<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Slim\Exceptions;

use Slim\Exception\HttpSpecializedException;

final class HttpException extends HttpSpecializedException {}
