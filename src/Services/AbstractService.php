<?php

namespace kalanis\google_maps\Services;


use kalanis\google_maps\Remote\Headers\ApiAuth;
use kalanis\google_maps\Remote\Headers\Language;
use Psr\Http\Message\RequestInterface;


/**
 * Google Maps Abstract Service
 *
 * Each basic call returns params to GET part of request
 * To fill HTTP headers, you must fill method "getHeaders()"
 * To fill request body, you must set data in "$body" variable
 *
 * Pass ApiAuth class is necessary to set Google API keys
 */
abstract class AbstractService
{
    protected const API_HOST = 'https://maps.googleapis.com';

    /**
     * Constructor
     *
     * @param RequestInterface $request Request to fill
     * @param ApiAuth $auth Class with auth params
     * @param Language $lang Class to set the language
     */
    public function __construct(
        protected RequestInterface  $request,
        protected readonly ApiAuth  $auth,
        protected readonly Language $lang,
    )
    {
    }

    /**
     * If parse response body for 'results' node
     * Usually necessary to discard that behavior if you want to get 'status' node
     *
     * @return bool
     */
    public function wantInnerResult(): bool
    {
        return true;
    }

    /**
     * @param string $path
     * @param array<string, string|int|float> $params
     * @return RequestInterface
     */
    protected function getWithDefaults(string $path, array $params): RequestInterface
    {
        $current = parse_url($path);
        $url = $this->request->getUri()
            ->withScheme($current['scheme'] ?? '')
            ->withUserInfo('')
            ->withHost($current['host'] ?? '')
            ->withPort(null)
            ->withPath($current['path'] ?? '')
            ->withQuery('')
            ->withFragment('')
        ;
        if (!empty($params)) {
            $url = $url->withQuery(http_build_query($params, '', null, PHP_QUERY_RFC3986));
        }
        return $this->request
            ->withMethod('GET')
            ->withUri($url)
        ;
    }

    /**
     * @param array<string, string|int|float> $params
     * @return array<string, string|int|float>
     */
    protected function queryParams(array $params): array
    {
        return array_merge($this->auth->getAuthParams(), $params);
    }

    /**
     * @param array<string, string|int|float> $params
     * @return array<string, string|int|float>
     */
    protected function queryParamsLang(array $params): array
    {
        return array_merge($this->auth->getAuthParams(), $this->lang->getToQuery('GET'), $params);
    }
}
