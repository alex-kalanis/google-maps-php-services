<?php

namespace kalanis\google_maps;


use kalanis\google_maps\Remote\Response;
use kalanis\google_maps\Services\ServiceFactory;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use ReflectionException;


/**
 * Main service class
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
class Services
{
    public function __construct(
        protected ServiceFactory  $factory,
        protected ClientInterface $client,
        protected Response        $response,
    )
    {
    }

    /**
     * Client methods refer to each service
     *
     * All service methods from Client calling would leave out the first argument (Client itself).
     *
     * @param string $method Client's method name
     * @param array<int, string|int|float> $arguments Method arguments
     * @throws ServiceException
     * @throws ReflectionException
     * @throws ClientExceptionInterface
     * @return array<mixed>|string Processed service method return
     */
    public function __call(string $method, array $arguments)
    {
        // walkthrough:
        // 1 - get service
        // 2 - call method to create Request object; pass params there
        // 3 - asks client for http data with things from correct service
        // 4 - client response with something
        // 5 - parse response and returns it to the user

        // Get the service from Factory
        $service = $this->factory->getService($method);
        $request = call_user_func_array([$service, $method], $arguments);
        if (!$request instanceof RequestInterface) {
            throw new ServiceException(sprintf('Call *%s::%s* cannot be used - returns *%s*!', get_class($service), $method, gettype($request)));
        }
        return $this->response->process(
            $this->client->sendRequest(
                $request
            ),
            $service->wantInnerResult()
        );
    }
}
