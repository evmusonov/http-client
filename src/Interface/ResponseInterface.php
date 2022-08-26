<?php

namespace Evmusonov\HttpClient\Interface;

interface ResponseInterface
{
    public function getBody(): array;

    public function getHeaders(): array;

    public function getHttpCode(): int;

    public function getErrorMessage(): ?string;
}