<?php

declare(strict_types=1);

namespace Microservices\Http\Handlers;

use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use Microservices\Exceptions\HttpException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

class HttpErrorHandler implements ErrorHandlerInterface
{
    /**
     * Constructor
     */
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * Invoke.
     *
     * @param ServerRequestInterface $request The request
     * @param Throwable $exception The exception
     * @param bool $displayErrorDetails Show error details
     * @param bool $logErrors Log errors
     * @param bool $logErrorDetails Log error details
     *
     * @return ResponseInterface The response
     * @throws JsonException
     */
    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        if ($logErrors) {
            $error = $this->getErrorDetails($exception, $displayErrorDetails);
            $error['method'] = $request->getMethod();
            $error['url'] = (string)$request->getUri();
            $this->logger->error($exception->getMessage(), $error);
        }

        $errorData = $this->getErrorDetails($exception, $displayErrorDetails);
        $statusCode = $this->getHttpStatusCode($exception);

        $error = [
            'statusCode' => $statusCode,
            'error' => $errorData
        ];

        $payload = json_encode(
            $error,
            JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS
        );

        $response = $this->responseFactory
            ->createResponse()
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);

        $response->getBody()->write($payload);

        return $response;
    }

    /**
     * Get http status code.
     *
     * @param Throwable $exception
     *
     * @return int The http code
     */
    private function getHttpStatusCode(Throwable $exception): int
    {
        // Detect status code
        $statusCode = StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
        }

        $file = basename($exception->getFile());
        if ($file === 'CallableResolver.php') {
            $statusCode = StatusCodeInterface::STATUS_NOT_FOUND;
        }

        return $statusCode;
    }

    /**
     * Get error message.
     *
     * @param Throwable $exception The error
     * @param bool $displayErrorDetails Display details
     *
     * @return array The error details
     */
    private function getErrorDetails(Throwable $exception, bool $displayErrorDetails): array
    {
        $output = [];

        if ($displayErrorDetails) {
            $output = [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            ];
        } else {
            $output = [
                'message' => $exception->getMessage(),
            ];
        }

        return $output;
    }
}
