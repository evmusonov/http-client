<?php

namespace Evmusonov\HttpClient\Interface;

interface ClientInterface
{
    public function request(string $method, string $uri, array $options = []): ResponseInterface;
}