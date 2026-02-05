<?php

namespace tests;


use external\Psr\Http\Client\ClientInterface;
use external\Psr\Http\Message\RequestInterface;
use external\Psr\Http\Message\ResponseInterface;


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
