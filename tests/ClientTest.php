<?php

namespace Evmusonov\Tests;

use Evmusonov\HttpClient\Client;

use Evmusonov\HttpClient\Exception\InvalidArgumentsException;
use Evmusonov\HttpClient\RequestOptions;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /**
     * free json API
     *
     * @var string
     */
    private string $baseUri = 'https://jsonplaceholder.typicode.com';

    public function testPositiveGetRequestWithoutOptions(): void
    {
        //arrange
        $client = new Client($this->baseUri);

        //act
        $response = $client->request(RequestOptions::GET_METHOD, '/posts');

        //assert
        $this->assertEquals(200, $response->getHttpCode());
        $this->assertNotEmpty($response->getBody());
    }

    public function testNegativeGetRequestWithoutOptions(): void
    {
        //arrange
        $client = new Client($this->baseUri);

        //act
        $response = $client->request(RequestOptions::GET_METHOD, '/postss');

        //assert
        $this->assertEmpty($response->getBody());
        $this->assertEquals(404, $response->getHttpCode());
    }

    public function testPositivePostRequest(): void
    {
        //arrange
        $client = new Client($this->baseUri);

        //act
        $response = $client->request(
            RequestOptions::POST_METHOD,
            '/posts',
            [
                RequestOptions::BODY => [
                    'title' => 'foo',
                    'body' => 'bar',
                    'userId' => 1,
                ],
            ]
        );

        //assert
        $this->assertNotEmpty($response->getBody());
        $this->assertEquals('foo', $response->getBody()['title']);
        $this->assertEquals(201, $response->getHttpCode());
    }

    public function testPositivePutRequest(): void
    {
        //arrange
        $client = new Client($this->baseUri);

        //act
        $response = $client->request(
            RequestOptions::PUT_METHOD,
            "/posts/1",
            [
                RequestOptions::BODY => [
                    'title' => 'foo2',
                    'body' => 'bar2',
                    'userId' => 1,
                ],
            ]
        );

        //assert
        $this->assertNotEmpty($response->getBody());
        $this->assertEquals('foo2', $response->getBody()['title']);
        $this->assertEquals(200, $response->getHttpCode());
    }

    public function testUriConsistsOfQueryParams(): void
    {
        //arrange
        $client = new Client($this->baseUri);

        //act
        $this->expectException(InvalidArgumentsException::class);
        $client->request(RequestOptions::GET_METHOD, "/posts?userId=1");
    }

    public function testIncorrectHttpMethod(): void
    {
        //arrange
        $client = new Client($this->baseUri);

        //act
        $this->expectException(InvalidArgumentsException::class);
        $client->request('patch', "/posts");
    }
}