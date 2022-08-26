<?php

namespace Evmusonov\HttpClient;

use Evmusonov\HttpClient\Interface\ResponseInterface;

class Response implements ResponseInterface
{
    public function __construct(
        private array $body,
        private array $headers,
        private int $httpCode,
        private ?string $errorMessage
    ) {
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}