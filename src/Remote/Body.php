<?php

namespace kalanis\google_maps\Remote;


use Psr\Http\Message\StreamInterface;
use RuntimeException;


/**
 * Simple class which represents body as stream
 */
class Body implements StreamInterface
{
    protected int $pointer = 0;

    public function __construct(
        protected string $content = '',
    )
    {
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public function close(): void
    {
    }

    /**
     * @return resource|null
     */
    public function detach()
    {
        return null;
    }

    public function tell(): int
    {
        return $this->pointer;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        $this->pointer = match ($whence) {
            SEEK_SET => $offset,
            SEEK_CUR => min($this->getSize(), $this->pointer + $offset),
            SEEK_END => $this->getSize(),
            default => throw new RuntimeException('Bad seek mode!')
        };
    }

    public function getSize(): int
    {
        return strlen($this->content);
    }

    public function rewind(): void
    {
        $this->pointer = 0;
    }

    public function isWritable(): bool
    {
        return true;
    }

    public function write(string $string): int
    {
        $this->content .= $string;
        return strlen($string);
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read(int $length): string
    {
        $newPos = $this->pointer + $length;
        $toReturn = substr($this->content, $this->pointer, $length);
        $this->pointer = min($newPos, $this->getSize());
        return $toReturn;
    }

    public function getContents(): string
    {
        return substr($this->content, $this->pointer);
    }

    /**
     * @param string|null $key
     * @return array|mixed|null
     */
    public function getMetadata(?string $key = null)
    {
        $available = [
            'timed_out' => false,
            'blocked' => false,
            'eof' => $this->eof(),
            'seekable' => $this->isSeekable(),
        ];
        if (!is_null($key)) {
            return $available[$key] ?? null;
        }
        return $available;
    }

    public function eof(): bool
    {
        return $this->getSize() <= $this->pointer;
    }

    public function isSeekable(): bool
    {
        return true;
    }
}
