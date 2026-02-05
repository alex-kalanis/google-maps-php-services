<?php

namespace tests;


use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


class XMockedResponse implements ClientInterface
{
    public function __construct(
        protected ResponseInterface $response
    )
    {
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->response;
    }
}
