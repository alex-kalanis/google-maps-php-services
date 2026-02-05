<?php

namespace tests;


use Psr\Http\Message\UriInterface;


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
