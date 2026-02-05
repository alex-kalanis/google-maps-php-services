<?php

namespace tests;


use kalanis\google_maps\Remote\Body;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;


class XRequest implements RequestInterface
{
    protected string $method = 'get';
    protected string $target = '/';
    protected array $headers = [];
    protected UriInterface $uri;
    protected StreamInterface|null $body = null;

    public function __construct()
    {
        $this->uri = new XUri();
    }

    public function getProtocolVersion(): string
    {
        throw new \Exception('mock');
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        throw new \Exception('mock');
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeader(string $name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine(string $name): string
    {
        return $this->hasHeader($name) ? $name . ':' . implode(',', $this->headers[$name]) : '';
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        $this->headers[$name] = [];
        $this->headers[$name][] = $value;
        return $this;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        if (!isset($this->headers[$name])) {
            $this->headers[$name] = [];
        }
        $this->headers[$name][] = $value;
        return $this;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        unset($this->headers[$name]);
        return $this;
    }

    public function getBody(): StreamInterface
    {
        return $this->body ?? new Body();
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $this->body = $body;
        return $this;
    }

    public function getRequestTarget(): string
    {
        return $this->target;
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $this->target = $requestTarget;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): RequestInterface
    {
        $this->method = $method;
        return $this;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $this->uri = $uri;
        return $this;
    }
}
