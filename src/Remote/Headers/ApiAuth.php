<?php

namespace kalanis\google_maps\Remote\Headers;


use kalanis\google_maps\ClientConfig;


/**
 * Google Maps PHP Client - API auth params
 */
class ApiAuth
{
    public function __construct(
        protected readonly ClientConfig $config,
    )
    {
    }

    public function getKey(): string
    {
        return $this->config->getApiKey();
    }

    /**
     * @return array<string, string>
     */
    public function getAuthParams(): array
    {
        return ['key' => $this->config->getApiKey()];
    }
}
