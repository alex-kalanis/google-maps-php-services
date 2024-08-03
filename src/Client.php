<?php

namespace kalanis\google_maps;


use Exception;
use kalanis\google_maps\Remote\Headers\ApiAuth;
use kalanis\google_maps\Remote\Headers\Language;
use kalanis\google_maps\Remote\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;


/**
 * Google Maps PHP Client - facade for processing
 *
 * @method array|string directions(string $origin, string $destination, array $params = [])
 * @method array|string distanceMatrix(string $origin, string $destination, array $params = [])
 * @method array|string elevation(string $locations, array $params = [])
 * @method array|string geocode(string $address, array $params = [])
 * @method array|string reverseGeocode(string $lat, string $lng, array $params = [])
 * @method array|string computeRoutes(array $origin, array $destination, array $body = [], array $headers = [], array $params = [])
 * @method array|string geolocate(array $bodyParams = [])
 * @method array|string timezone(string $location, string|null $timestamp = null, array $params = [])
 * @method array|string nearby(string $keyword, float[] $latlng, float|null $radius = null, string|null $type = null, array $params = [])
 * @method array|string findPlace(string $input, string $inputType, string[] $fields = [], float[]|null $bias = null, array $params = [])
 * @method array|string findText(string $query, float $radius, float[] $location = [], int|null $maxPrice = null, int|null $minPrice = null, bool $openNow = false, string|null $region = null, string|null $type = null, array $params = [])
 * @method array|string placeDetails(string $placeId, string[] $fields = [], string $region = null, bool $translateReviews = true, string $sortReviews = null, array $params = [])
 * @method array|string snapToRoads(array|string|null $path, array $params = [])
 */
class Client
{
    protected Services $services;
    protected ClientConfig $config;

    /**
     * @param RequestInterface $request
     * @param ClientInterface $client
     * @param ClientConfig $config
     * Each of these three must be available via Dependency Injection
     */
    public function __construct(
        RequestInterface $request,
        ClientInterface  $client,
        ClientConfig     $config,
    )
    {
        $this->config = $config;
        $this->services = new Services(
            new Services\ServiceFactory(
                $request,
                new ApiAuth($config),
                new Language($config)
            ),
            $client,
            new Response()
        );
    }

    /**
     * Set default language for Google Maps API
     *
     * @param string|null $language ex. 'zh-TW'
     * @return $this
     */
    public function setLanguage(?string $language = null): self
    {
        $this->config->setLanguage($language);
        return $this;
    }

    /**
     * Client methods refer to each service
     *
     * All service methods from Client calling would leave out the first argument (Client itself).
     *
     * @param string $method Client's method name
     * @param array<int, string|int|float> $arguments Method arguments
     * @throws Exception
     * @return mixed Current service method return
     */
    public function __call(string $method, array $arguments)
    {
        return $this->services->__call($method, $arguments);
    }
}
