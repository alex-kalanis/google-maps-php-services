<?php

namespace kalanis\google_maps\Services;


use kalanis\google_maps\ServiceException;
use Psr\Http\Message\RequestInterface;


/**
 * Directions Service
 *
 * @see     https://developers.google.com/maps/documentation/elevation/
 * @see     https://developers.google.com/maps/documentation/elevation/requests-elevation
 */
class Elevation extends AbstractService
{
    /**
     * Elevation
     *
     * @param string|array<string|int, float> $locations
     * @param array<string, string|int|float> $params Query parameters
     * @throws ServiceException
     * @return RequestInterface
     */
    public function elevation(string|array $locations, array $params = []): RequestInterface
    {
        // `locations` seems to only allow `lat,lng` pattern
        if (is_string($locations)) {
            $params['locations'] = $locations;

        } elseif (isset($locations['lat']) && isset($locations['lng'])) {
            $params['locations'] = sprintf('%1.08F,%1.08F', $locations['lat'], $locations['lng']);

        } elseif (isset($locations[0]) && isset($locations[1])) {
            $params['locations'] = sprintf('%1.08F,%1.08F', $locations[0], $locations[1]);

        } else {
            throw new ServiceException('Passed invalid values into coordinates! You must use either preformatted string or array with lat and lng or 0 and 1 keys.');

        }

        return $this->getWithDefaults(
            static::API_HOST . '/maps/api/elevation/json',
            $this->queryParamsLang($params)
        );
    }
}
