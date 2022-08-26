<?php

namespace Evmusonov\HttpClient;

use Evmusonov\HttpClient\Interface\RequestInterface;

class Request implements RequestInterface
{
    public function __construct(
        private string $method,
        private string $uri,
        private array $headers,
        private array $body
    ) {
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): array
    {
        return $this->body;
    }
}