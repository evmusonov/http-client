<?php

namespace Evmusonov\HttpClient;

/**
 * Вспомагательный класс клиента
 */
abstract class RequestOptions
{
    public const GET_METHOD = 'GET';
    public const POST_METHOD = 'POST';
    public const PUT_METHOD = 'PUT';
    public const DELETE_METHOD = 'DELETE';
    public const HTTP_METHODS = [
        self::GET_METHOD,
        self::POST_METHOD,
        self::PUT_METHOD,
        self::DELETE_METHOD,
    ];

    public const QUERY_PARAMS = 'query_params';
    public const HEADERS = 'headers';
    public const BODY = 'body';
}