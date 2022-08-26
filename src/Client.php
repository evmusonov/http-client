<?php

namespace Evmusonov\HttpClient;

use Evmusonov\HttpClient\Exception\InvalidArgumentsException;
use Evmusonov\HttpClient\Interface\ClientInterface;
use Evmusonov\HttpClient\Interface\ResponseInterface;

class Client implements ClientInterface
{
    private string $baseUri;

    public function __construct(string $baseUri)
    {
        $this->baseUri = str_ends_with($baseUri, "/") ? $baseUri : "$baseUri/";
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @throws InvalidArgumentsException
     * @return ResponseInterface
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        $headers = $options[RequestOptions::HEADERS] ?? [];
        $body = $options[RequestOptions::BODY] ?? [];
        $queryParams = $options[RequestOptions::QUERY_PARAMS] ?? [];

        $this->checkMethod($method);
        $request = new Request($method, $this->makeUri($uri, $queryParams), $headers, $body);

        $curl = new Curl($request);
        $curl->exec();

        return $this->createResponse($curl);
    }

    /**
     * @param string $uri
     * @param array $queryParams
     * @return string
     * @throws InvalidArgumentsException
     */
    private function makeUri(string $uri, array $queryParams): string
    {
        $uri = str_starts_with($uri, '/') ? mb_substr($uri, 1) : $uri;
        if (str_contains($uri, '?')) {
            throw new InvalidArgumentsException("Uri строка не должна содержать query параметров");
        }

        $queryString = http_build_query($queryParams, null, '&', PHP_QUERY_RFC3986);

        return sprintf("%s%s%s", $this->baseUri, $uri, $queryString ? "?$queryString" : $queryString);
    }

    /**
     * @param string $method
     * @return void
     */
    private function checkMethod(string $method): void
    {
        if (!in_array($method, RequestOptions::HTTP_METHODS, 1)) {
            throw new InvalidArgumentsException("Указан неверный HTTP метод");
        }
    }

    /**
     *
     * @param Curl $curl
     * @return ResponseInterface
     */
    private function createResponse(Curl $curl): ResponseInterface
    {
        $curlResponse = $curl->getResponse();
        $headerSize = $curl->getInfo()["header_size"];

        $body = mb_substr($curlResponse, $headerSize);
        $body = json_decode($body, 1);

        $headerContent = substr($curlResponse, 0, $headerSize);
        $headers = $this->getHeadersFromCurlResponse($headerContent);

        return new Response($body, $headers, $curl->getInfo()['http_code'], $curl->getError());
    }

    /**
     * Преобразует строку заголовков в массив
     *
     * @param string $headerContent
     * @return array
     */
    private function getHeadersFromCurlResponse(string $headerContent): array
    {
        $headers = [];
        $arrRequests = explode("\r\n\r\n", $headerContent);

        // Loop of response headers. The "count() - 1" is to
        // avoid an empty row for the extra line break before the body of the response.
        for ($index = 0; $index < count($arrRequests) - 1; $index++) {
            foreach (explode("\r\n", $arrRequests[$index]) as $i => $line) {
                if ($i === 0) {
                    $headers[$index]['http_code'] = $line;
                } else {
                    list ($key, $value) = explode(': ', $line);
                    $headers[$index][$key] = $value;
                }
            }
        }

        return $headers;
    }
}