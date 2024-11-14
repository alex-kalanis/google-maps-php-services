<?php

namespace kalanis\google_maps\Services;


use kalanis\google_maps\Remote\Headers\ApiAuth;
use kalanis\google_maps\Remote\Headers\Language;
use kalanis\google_maps\ServiceException;
use Psr\Http\Message\RequestInterface;
use ReflectionClass;
use ReflectionException;


/**
 * Google Maps PHP Client - factory to get services
 **/
class ServiceFactory
{
    /**
     * For Client-Service API method director
     *
     * @var array<string, class-string> Method => Service Class name
     */
    protected array $serviceMethodMap = [
        'directions' => Directions::class,
        'distanceMatrix' => DistanceMatrix::class,
        'elevation' => Elevation::class,
        'findPlace' => FindPlace::class,
        'findText' => FindText::class,
        'geocode' => Geocoding::class,
        'reverseGeocode' => Geocoding::class,
        'geolocate' => Geolocation::class,
        'nearby' => Nearby::class,
        'placeDetails' => PlaceDetails::class,
        'snapToRoads' => Roads::class,
        'computeRoutes' => Routes::class,
        'timezone' => Timezone::class,
    ];

    public function __construct(
        protected readonly RequestInterface $request,
        protected readonly ApiAuth          $apiAuth,
        protected readonly Language         $lang,
    )
    {
    }

    /**
     * @param string|object $method
     * @throws ReflectionException
     * @throws ServiceException
     * @return AbstractService
     */
    public function getService(string|object $method): AbstractService
    {
        if (is_object($method)) {
            if (!is_a($method, AbstractService::class)) {
                throw new ServiceException(sprintf('Service *%s* is not an instance of \kalanis\google_maps\Services\AbstractService', $method::class), 400);
            }
            return $method;
        }

        // Matching self::$serviceMethodMap is required
        if (!isset($this->serviceMethodMap[$method])) {
            throw new ServiceException(sprintf('Call to undefined service method *%s*', $method), 400);
        }

        // Get the service mapped by method
        $service = $this->serviceMethodMap[$method];

        $reflection = new ReflectionClass($service);
        $instance = $reflection->newInstance($this->request, $this->apiAuth, $this->lang);

        if (!$instance instanceof AbstractService) {
            throw new ServiceException(sprintf('Service *%s* is not an instance of \kalanis\google_maps\Services\AbstractService', $service), 400);
        }

        return $instance;
    }
}
