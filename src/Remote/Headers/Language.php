<?php

namespace kalanis\google_maps\Remote\Headers;


use kalanis\google_maps\ClientConfig;


/**
 * Class for work with language params
 */
class Language
{
    public function __construct(
        protected ClientConfig $config,
    )
    {
    }

    /**
     * @param string $method
     * @return array<string, string>
     */
    public function getToQuery(string $method): array
    {
        $params = [];
        if ($this->canAddLanguage($method) && !empty($this->config->getLanguage())) {
            $params['language'] = $this->config->getLanguage();
        }
        return $params;
    }

    protected function canAddLanguage(string $method): bool
    {
        return ('GET' == strtoupper($method)) && $this->config->getLanguage();
    }

    public function getLanguage(): ?string
    {
        return $this->config->getLanguage();
    }
}
