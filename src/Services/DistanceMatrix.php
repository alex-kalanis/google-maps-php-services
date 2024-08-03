<?php

namespace kalanis\google_maps\Services;


use Psr\Http\Message\RequestInterface;


/**
 * Directions Service
 *
 * @see     https://developers.google.com/maps/documentation/distance-matrix/
 * @see     https://developers.google.com/maps/documentation/distance-matrix/distance-matrix
 */
class DistanceMatrix extends AbstractService
{
    /**
     * Distance matrix
     *
     * @param string $origins
     * @param string $destinations
     * @param array<string, string|int|float> $params Query parameters
     * @return RequestInterface
     */
    public function distanceMatrix(string $origins, string $destinations, array $params = []): RequestInterface
    {
        // Parameters for Language setting
        $params['origins'] = $origins;
        $params['destinations'] = $destinations;

        return $this->getWithDefaults(
            static::API_HOST . '/maps/api/distancematrix/json',
            $this->queryParamsLang($params)
        );
    }
}
