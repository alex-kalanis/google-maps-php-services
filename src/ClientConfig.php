<?php

namespace kalanis\google_maps;


/**
 * Configuration class
 */
class ClientConfig
{
    public function __construct(
        protected string      $apiKey,
        protected string|null $language = null,
    )
    {
    }

    /**
     * Initialize configuration
     * @param string|array{
     *     key: string,
     *     language: string|null,
     * } $optParams
     *      key: Google API Key,
     *      language: Default language
     * @throws ServiceException
     * @return self
     */
    public static function init(string|array $optParams): ClientConfig
    {
        return new self(
            is_array($optParams) && (isset($optParams['key']))
                ? strval($optParams['key'])
                : (is_string($optParams)
                ? $optParams
                : throw new ServiceException('Unable to set Client credential due to your wrong params', 400)
            ),
            is_array($optParams) && (isset($optParams['language'])) ? strval($optParams['language']) : null,
        );
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): void
    {
        $this->language = $language;
    }
}
