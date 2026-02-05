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
 * @method array<mixed>|string directions(string $origin, string $destination, array<string, string|int|float> $params = [])
 * @method array<mixed>|string distanceMatrix(string $origin, string $destination, array<string, string|int|float> $params = [])
 * @method array<mixed>|string elevation(string|array<string|int, float> $locations, array<string, string|int|float> $params = [])
 * @method array<mixed>|string geocode(string|null $address, array<string, string|int|float> $params = [])
 * @method array<mixed>|string reverseGeocode(array<string|float>|string $latlng, array<string, string|int|float> $params = [])
 * @method array<mixed>|string computeRoutes(array<mixed> $origin, array<mixed> $destination, array<mixed> $body = [])
 * @method array<mixed>|string geolocate(array<mixed> $bodyParams = [])
 * @method array<mixed>|string timezone(string|array<string> $location, int|null $timestamp = null, array<string, string|int|float> $params = [])
 * @method array<mixed>|string nearby(string $keyword, array<string|int, float> $latlng, float|null $radius = null, string|null $type = null, array<string, string|int|float> $params = [])
 * @method array<mixed>|string findPlace(string $input, string $inputType, string[] $fields = [], array<string|int, float>|null $bias = null, array<string, string|int|float> $params = [])
 * @method array<mixed>|string findText(string $query, float $radius, array<string|int, float> $location = [], int<0, 4>|null $maxPrice = null, int<0, 4>|null $minPrice = null, bool $openNow = false, string|null $region = null, string|null $type = null, array<string, string|int|float> $params = [])
 * @method array<mixed>|string placeDetails(string $placeId, string[] $fields = [], string|null $region = null, bool $translateReviews = true, string|null $sortReviews = null, array<string, string|int|float> $params = [])
 * @method array<mixed>|string snapToRoads(array<int, float[]>|string|null $path, array<string, string|int|float> $params = [])
 */
class Services
{
    public function __construct(
        protected readonly ServiceFactory  $factory,
        protected readonly ClientInterface $client,
        protected readonly Response        $response,
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
     * @return mixed Processed service method return
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
