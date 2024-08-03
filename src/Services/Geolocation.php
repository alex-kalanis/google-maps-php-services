<?php

namespace kalanis\google_maps\Services;


use kalanis\google_maps\Remote\Body;
use kalanis\google_maps\ServiceException;
use Psr\Http\Message\RequestInterface;


/**
 * Directions Service
 *
 * @see     https://developers.google.com/maps/documentation/geolocation/
 * @see     https://developers.google.com/maps/documentation/geolocation/requests-geolocation
 */
class Geolocation extends AbstractService
{
    /**
     * Geolocate
     *
     * @param array<mixed> $bodyParams Body parameters
     * @throws ServiceException
     * @return RequestInterface
     */
    public function geolocate(array $bodyParams = []): RequestInterface
    {
        // Google API request body format
        $encoded = json_encode($bodyParams, JSON_UNESCAPED_SLASHES);
        if (false === $encoded) {
            // @codeCoverageIgnoreStart
            // to get this error you must have something really fishy in $bodyParams
            throw new ServiceException(json_last_error_msg());
        }
        // @codeCoverageIgnoreEnd

        return $this->getWithDefaults(
                'https://www.googleapis.com/geolocation/v1/geolocate',
                $this->queryParams([])
            )
            ->withMethod('POST')
            ->withBody(new Body($encoded));
    }
}
