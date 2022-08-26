<?php

namespace Evmusonov\HttpClient;

use Evmusonov\HttpClient\Interface\RequestInterface;

class Curl
{
    private \CurlHandle $curlHandle;

    private ?string $errorMessage = null;

    private string $response;

    private array $info = [];

    public function __construct(RequestInterface $request)
    {
        $this->curlHandle = curl_init($request->getUri());
        $this->processOptions($request);
    }

    /**
     * Выполняет запрос
     *
     * @return void
     */
    public function exec(): void
    {
        $this->response = curl_exec($this->curlHandle);
        $this->info = curl_getinfo($this->curlHandle);

        if(curl_error($this->curlHandle)) {
            $this->errorMessage = curl_error($this->curlHandle);
        }

        curl_close($this->curlHandle);
    }

    public function getError(): ?string
    {
        return $this->errorMessage;
    }

    public function hasError(): bool
    {
        return !empty($this->errorMessage);
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function getInfo(): array
    {
        return $this->info;
    }

    /**
     * Проставляет переданные данные (GET параметры, тело и заголовки)
     *
     * @param RequestInterface $request
     * @return void
     */
    private function processOptions(RequestInterface $request): void
    {
        $options[CURLOPT_HEADER] = 1;
        $options[CURLOPT_RETURNTRANSFER] = 1;
        $options[CURLOPT_CUSTOMREQUEST] = $request->getMethod();

        if ($request->getHeaders()) {
            foreach ($request->getHeaders() as $headerName => $headerValue) {
                $options[CURLOPT_HTTPHEADER][] = "$headerName: $headerValue";
            }
        }

        if ($request->getBody()) {
            $options[CURLOPT_POSTFIELDS] = http_build_query($request->getBody(), '', '&');
        }

        curl_setopt_array($this->curlHandle, $options);
    }
}