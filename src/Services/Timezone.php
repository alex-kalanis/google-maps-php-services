<?php

namespace kalanis\google_maps\Services;


use kalanis\google_maps\ServiceException;
use Psr\Http\Message\RequestInterface;


/**
 * Timezone Service
 *
 * @see     https://developers.google.com/maps/documentation/timezone/
 */
class Timezone extends AbstractService
{
    /**
     * Timezone
     *
     * @param string|array<string> $location
     * @param int|null $timestamp
     * @param array<string, string|int|float> $params Query parameters
     * @throws ServiceException
     * @return RequestInterface
     */
    public function timezone(string|array $location, ?int $timestamp = null, array $params = []): RequestInterface
    {
        // `location` seems to only allow `lat,lng` pattern
        if (is_string($location)) {

            $params['location'] = $location;

        } else {

            if (isset($location['lat']) && isset($location['lng'])) {

                $params['location'] = sprintf('%1.08F,%1.08F', $location['lat'], $location['lng']);

            } elseif (isset($location[0]) && isset($location[1])) {

                $params['location'] = sprintf('%1.08F,%1.08F', $location[0], $location[1]);

            } else {

                throw new ServiceException('Passed invalid values into coordinates! You must use either array with lat and lng or 0 and 1 keys.');

            }
        }

        // Timestamp
        $params['timestamp'] = ($timestamp) ?: time();

        return $this->getWithDefaults(
            static::API_HOST . '/maps/api/timezone/json',
            $this->queryParamsLang($params)
        );
    }
}
