<?php

namespace Evmusonov\HttpClient\Interface;

interface RequestInterface
{
    public function getMethod(): string;

    public function getUri(): string;

    public function getHeaders(): array;

    public function getBody(): array;
}