<?php

namespace kalanis\google_maps\Services;


use Psr\Http\Message\RequestInterface;


/**
 * Directions Service
 *
 * @see     https://developers.google.com/maps/documentation/directions/
 * @see     https://developers.google.com/maps/documentation/directions/get-directions
 */
class Directions extends AbstractService
{
    /**
     * Directions
     *
     * @param string $origin
     * @param string $destination
     * @param array<string, string|int|float> $params Query parameters
     * @return RequestInterface
     */
    public function directions(string $origin, string $destination, array $params = []): RequestInterface
    {
        $params['origin'] = $origin;
        $params['destination'] = $destination;

        return $this->getWithDefaults(
            static::API_HOST . '/maps/api/directions/json',
            $this->queryParamsLang($params)
        );
    }
}
