<?php

namespace kalanis\google_maps\Services;


use kalanis\google_maps\Remote\Body;
use kalanis\google_maps\ServiceException;
use Psr\Http\Message\RequestInterface;


/**
 * Routes service
 *
 * @see https://developers.google.com/maps/documentation/routes/
 * @see https://developers.google.com/maps/documentation/routes/compute_route_directions
 */
class Routes extends AbstractService
{
    /**
     * Route lookup
     *
     * @param array<mixed>|null $origin
     * @param array<mixed>|null $destination
     * @param array<mixed> $body Full body
     * @throws ServiceException
     * @return RequestInterface
     */
    public function computeRoutes(array|null $origin, array|null $destination, $body = []): RequestInterface
    {
        $requestBody = $body;
        $requestBody['origin'] = $origin ?? $requestBody['origin'] ?? [];
        $requestBody['destination'] = $destination ?? $requestBody['destination'] ?? [];

        // Language Code
        if (!empty($this->lang->getLanguage())) {
            $requestBody['languageCode'] = $this->lang->getLanguage();
        }

        // Google API request body format
        $encoded = @json_encode($requestBody);
        if (false === $encoded) {
            // @codeCoverageIgnoreStart
            // to get this error you must have something really fishy in $bodyParams
            throw new ServiceException(json_last_error_msg());
        }
        // @codeCoverageIgnoreEnd

        return $this->request
            ->withMethod('POST')
            ->withRequestTarget('https://routes.googleapis.com/directions/v2:computeRoutes')
            ->withHeader('X-Goog-FieldMask', 'routes.duration,routes.distanceMeters,routes.legs,geocodingResults')
            ->withHeader('X-Goog-Api-Key', $this->auth->getKey())
            ->withBody(new Body($encoded));
    }
}
