<?php

namespace tests;


use kalanis\google_maps\Remote\Body;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;


class XResponse implements ResponseInterface
{
    public function __construct(
        protected int $code,
        protected string $data,
    )
    {
    }

    public function getProtocolVersion(): string
    {
        throw new \Exception('mock');
    }

    public function withProtocolVersion($version): MessageInterface
    {
        throw new \Exception('mock');
    }

    public function getHeaders(): array
    {
        throw new \Exception('mock');
    }

    public function hasHeader($name): bool
    {
        throw new \Exception('mock');
    }

    public function getHeader($name): array
    {
        throw new \Exception('mock');
    }

    public function getHeaderLine($name): string
    {
        throw new \Exception('mock');
    }

    public function withHeader($name, $value): MessageInterface
    {
        throw new \Exception('mock');
    }

    public function withAddedHeader($name, $value): MessageInterface
    {
        throw new \Exception('mock');
    }

    public function withoutHeader($name): MessageInterface
    {
        throw new \Exception('mock');
    }

    public function getBody(): StreamInterface
    {
        return new Body($this->data);
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        throw new \Exception('mock');
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }

    public function withStatus($code, $reasonPhrase = ''): ResponseInterface
    {
        throw new \Exception('mock');
    }

    public function getReasonPhrase(): string
    {
        throw new \Exception('mock');
    }
}
