<?php

use PHPUnit\Framework\TestCase;
use kalanis\google_maps\Remote\Body;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;


/**
 * Class CommonTestClass
 * The structure for mocking and configuration seems so complicated, but it's necessary to let it be totally idiot-proof
 */
class CommonTestClass extends TestCase
{
}


class XUri implements UriInterface
{
    protected string $scheme = '';
    protected string $host = '';
    protected string $path = '';
    protected string $query = '';

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        throw new \Exception('mock');
    }

    public function getUserInfo(): string
    {
        throw new \Exception('mock');
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        throw new \Exception('mock');
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        throw new \Exception('mock');
    }

    public function withScheme(string $scheme): UriInterface
    {
        $this->scheme = $scheme;
        return $this;
    }

    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        return $this;
    }

    public function withHost(string $host): UriInterface
    {
        $this->host = $host;
        return $this;
    }

    public function withPort(?int $port): UriInterface
    {
        return $this;
    }

    public function withPath(string $path): UriInterface
    {
        $this->path = $path;
        return $this;
    }

    public function withQuery(string $query): UriInterface
    {
        $this->query = $query;
        return $this;
    }

    public function withFragment(string $fragment): UriInterface
    {
        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s%s%s%s',
        empty($this->scheme) ? '' : $this->scheme . '://',
            $this->host,
            $this->path,
            empty($this->query) ? '' : '?' . $this->query
        );
    }
}


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
