<p align="center">
    <a href="https://cloud.google.com/maps-platform/" target="_blank">
        <img src="https://cloud.google.com/images/maps-platform/google-maps-lockup.svg" width="300px">
    </a>
    <h1 align="center">Google Maps Services <i>for</i> PHP</h1>
    <br>
</p>

PHP client library(SDK) for Google Maps API Web Services.

Fork of older Nick Tsai's library.

![Build Status](https://github.com/alex-kalanis/google-maps-php-services/actions/workflows/code_checks.yml/badge.svg)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-kalanis/google-maps-php-services/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-kalanis/google-maps-php-services/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/alex-kalanis/google-maps-php-services/v/stable.svg?v=1)](https://packagist.org/packages/alex-kalanis/google-maps-php-services)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)](https://php.net/)
[![Downloads](https://img.shields.io/packagist/dt/alex-kalanis/google-maps-php-services.svg?v1)](https://packagist.org/packages/alex-kalanis/google-maps-php-services)
[![License](https://poser.pugx.org/alex-kalanis/google-maps-php-services/license.svg?v=1)](https://packagist.org/packages/alex-kalanis/google-maps-php-services)
[![Code Coverage](https://scrutinizer-ci.com/g/alex-kalanis/google-maps-php-services/badges/coverage.png?b=master&v=1)](https://scrutinizer-ci.com/g/alex-kalanis/google-maps-php-services/?branch=master)

### Differences:

- Minimal php is 8.1
- Type checks
- Dependency injection
- With PSR and other remote libraries than Guzzle/Curl in mind
- Only API key now usable


## OUTLINE

- [Demonstration](#demonstration)
- [Description](#description)
- [Requirements](#requirements)
    - [API keys](#api-keys)
- [Installation](#installation)
- [Usage](#usage)
    - [Client](#client)
        - [Language](#language)
    - [Directions API](#directions-api)
    - [Distance Matrix API](#distance-matrix-api)
    - [Routes API](#routes-api)
    - [Elevation API](#elevation-api)
    - [Geocoding API](#geocoding-api)
    - [Geolocation API](#geolocation-api)
    - [Time Zone API](#time-zone-api)
    - [Nearby API](#nearby-api)
    - [Find by Place API](#find-by-place-api)
    - [Find by Text API](#find-by-text-api)
    - [Place details API](#place-details-api)

---

## DEMONSTRATION

For nearly any raw php / custom frameworks:

```php
// somewhere in configuration something like this

/// ... with anonymous function as setter in DI
function (): \kalanis\google_maps\ClientConfig
{
    return \kalanis\google_maps\ClientConfig::init('Your API Key');
}
```

Then on desired pages:

```php
class YourPresenter extends YourFramework
{
    public function __construct(
        // ... other used classes
        protected kalanis\google_maps\Client $mapService,
    ) {
    }

    public function process(): void
    {
        // Geocoding an address
        $geocodeResult = $this->mapService->geocode('Pelješki most, Croatia');

        // Look up an address with reverse geocoding
        $reverseGeocodeResult = $this->mapService->reverseGeocode(42.916667, 17.533333);

        // Request directions via public transit
        $directionsResult = $this->mapService->directions('Ploče', 'Dubrovnik', [
            'mode' => "transit",
            'departure_time' => time(),
        ]);
    }
}
```

For [Laravel](https://laravel.com/):

```php
/// app\Providers\AppServiceProvider.php
public function register()
{
    // ... other binds
    $this->app()->bind(\kalanis\google_maps\Client::class, function(
            Psr\Http\Client\ClientInterface $client,
            Psr\Http\Message\RequestInterface $request
        ) {
            return new \kalanis\google_maps\Client(
                $request,
                $client,
                \kalanis\google_maps\ClientConfig::init('Your API Key'),
            );
        }
    );
}
```

And then in the controller is the code the same as in another random framework.

For [Symfony](https://symfony.com/):

```yaml
# config/services.yaml
services:
  kalanis\google_maps\ClientConfig:
    arguments: ['Your API Key']
  kalanis\google_maps\Client:
    arguments: ['@Psr\Http\Message\RequestInterface', '@Psr\Http\Client\ClientInterface', '@kalanis\google_maps\ClientConfig']
```

```php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/your-maps')]
class YourMapsPresenter extends AbstractController
{
    public function __construct(
        // ... other used classes
        protected kalanis\google_maps\Client $mapService,
    ) {
    }

    #[Route('/geocode', name: 'maps_geocode', defaults: ['where' => 'Pelješki most, Croatia'], methods: ['GET'])]
    public function geocode(Request $request, string $where): Response
    {
        $geocodeResult = $this->mapService->geocode($where);
        return new JsonResponse($geocodeResult)
    }

    #[Route('/reverse-geocode', lat: '42.916667', lon: '17.533333', defaults: ['lat' => '42.916667', 'lon' => '17.533333'], methods: ['GET'])]
    public function reverseGeocode(Request $request, string $lat, string $lon): Response
    {
        $geocodeResult = $this->mapService->reverseGeocode([$lat, $lon]);
        return new JsonResponse($geocodeResult)
    }
}
```

For [Nette](https://nette.org/):
```neon
services:
    # ... other ones
    - kalanis\google_maps\ClientConfig('Your API Key')
    - kalanis\google_maps\Client
    # ... other ones
```

Or with parameters for different servers/services:

```neon
parameters:
    # ... other ones
    googleMapsKey: 'Your API Key'
    # ... other ones

services:
    # ... other ones
    - kalanis\google_maps\ClientConfig(%parameters.googleMapsKey%)
    - kalanis\google_maps\Client
    # ... other ones
```

And then in class like in other frameworks with DI.


---


## DESCRIPTION

The PHP Client for Google Maps Services is a PHP Client library for the following
[Google Maps APIs](https://developers.google.com/maps):

- Maps
    - [Elevation API](#elevation-api) ([Google Doc](https://developers.google.com/maps/documentation/elevation/))
- Routes
    - [Routes API](#routes-api) ([Google Doc](https://developers.google.com/maps/documentation/routes))
    - [Roads API](#roads-api) ([Google Doc](https://developers.google.com/maps/documentation/roads))
    - [Directions API](#directions-api) ([Google Doc](https://developers.google.com/maps/documentation/directions/))
    - [Distance Matrix API](#distance-matrix-api) ([Google Doc](https://developers.google.com/maps/documentation/distancematrix/))
- Places
    - [Geocoding API](#geocoding-api) ([Google Doc](https://developers.google.com/maps/documentation/geocoding/))
    - [Geolocation API](#geolocation-api) ([Google Doc](https://developers.google.com/maps/documentation/geolocation/))
    - [Time Zone API](#time-zone-api) ([Google Doc](https://developers.google.com/maps/documentation/timezone/))
    - [Nearby API](#nearby-api) ([Google Doc](https://developers.google.com/maps/documentation/places/web-service/search-nearby/))
    - [Find by Place API](#find-by-place-api) ([Google Doc](https://developers.google.com/maps/documentation/places/web-service/search-find-place))
    - [Find by Text API](#find-by-text-api) ([Google Doc](https://developers.google.com/maps/documentation/places/web-service/search-text))
    - [Place details API](#place-details-api) ([Google Doc](https://developers.google.com/maps/documentation/places/web-service/details))

---

## REQUIREMENTS

- PHP 8.1+ or higher

### API keys

Each Google Maps Web Service request requires an API key or client ID. API keys
are freely available with a Google Account at
https://developers.google.com/console. The type of API key you need is a
**Server key**.

To get an API key:

 1. Visit https://developers.google.com/console and log in with
    a Google Account.
 2. Select one of your existing projects, or create a new project.
 3. Enable the Google Maps Services API(s) you plan to use, such as:
    * Directions API
    * Distance Matrix API
    * Geocoding API
    * Places API
    * Roads API
    * Time Zone API
    * Nearby API

 4. Create a new **Server key**.
 5. If you'd like to restrict requests to a specific IP address, do so now.

For guided help, follow the instructions for the [Directions API][directions-key].
You only need one API key, but remember to enable all the APIs you need.
For even more information, see the guide to [API keys][api-key].

**Important:** This key should be kept secret on your server.

---

## INSTALLATION

Run Composer in your project:

    composer require alex-kalanis/google-maps-php-services

Then you could call it after Composer is loaded depended on your PHP framework:

```php
require __DIR__ . '/vendor/autoload.php';

use kalanis\google_maps\Client;
```

---

## USAGE

Before using any Google Maps Services, first you need to create a Client with configuration,
then use the client to access Google Maps Services.

### Client

Create a Client using [API key][api-key]:

```php
$gmaps = new \kalanis\google_maps\Client(
    new \PsrMock\Psr7\Request(),
    new \PsrMock\Psr18\Client(),
    new \kalanis\google_maps\ConfigClient('Your API Key'),
);
```

#### Language

You could set language for Client for all services:

```php
$gmaps = new \kalanis\google_maps\Client(
    new \PsrMock\Psr7\Request(),
    new \PsrMock\Psr18\Client(),
    new \kalanis\google_maps\ConfigClient('Your API Key', 'pt-br'),
);
```

> [list of supported languages - Google Maps Platform](https://developers.google.com/maps/faq#languagesupport)

### APIs

#### Elevation API

[Elevation API overview | Google for Developers](https://developers.google.com/maps/documentation/elevation/overview)

```php
// Get elevation by locations parameter
$elevationResult = $gmaps->elevation(25.0339639, 121.5644722);
$elevationResult = $gmaps->elevation('25.0339639', '121.5644722');
```

#### Routes API

[Get a route | Google for Developers](https://developers.google.com/maps/documentation/routes/compute_route_directions)

```php
$routes = $gmaps->computeRoutes($originArray, $destinationArray, $fullBodyArray, $fieldMask)

// Get the route data between two places simply
$routes = $gmaps->computeRoutes([
        "location" => [
           "latLng" => [
              "latitude" => 37.419734,
              "longitude" => -122.0827784
           ]
        ]
    ],
    [
        "location" => [
           "latLng" => [
              "latitude" => 37.41767,
              "longitude" => -122.079595
           ]
        ]
    ]);

// Get the full route data between two places with full request data
$routes = $gmaps->computeRoutes([...], [...], ["travelMode": "DRIVE", ...], '*');
```

#### Roads API

[Snap to Roads  | Google for Developers](https://developers.google.com/maps/documentation/roads/snap)

```php
$roads = $gmaps->snapToRoads([[-35.27801,149.12958], [-35.28032,149.12907], [-35.28099,149.12929]]);
```

#### Directions API

[Getting directions | Google for Developers](https://developers.google.com/maps/documentation/directions/get-directions)

```php
// Request directions via public transit
$directionsResult = $gmaps->directions('Milano', 'Venezia', [
    'mode' => "transit",
    'departure_time' => time(),
]);
```


#### Distance Matrix API

[Get started with the Distance Matrix API | Google for Developers](https://developers.google.com/maps/documentation/distance-matrix/start)

```php
// Get the distance matrix data between two places
$distanceMatrixResult = $gmaps->distanceMatrix('Canberra', 'Perth');

// With Imperial units
$distanceMatrixResult = $gmaps->distanceMatrix('Stonehenge', 'Bristol', [
    'units' => 'imperial',
]);
```

#### Geocoding API

[Geocoding API overview | Google for Developers](https://developers.google.com/maps/documentation/geocoding/overview)

```php
// Geocoding an address
$geocodeResult = $gmaps->geocode('Avenida Maracanã 350, Rio de Janeiro');

// Look up an address with reverse geocoding
$reverseGeocodeResult = $gmaps->reverseGeocode(-22.912167, -43.230164);
```

#### Geolocation API

[Geolocation API overview | Google for Developers](https://developers.google.com/maps/documentation/geolocation/overview)

```php
// Simple geolocate
$geolocateResult = $gmaps->geolocate([]);
```

#### Time Zone API

[Time Zone API overview | Google for Developers](https://developers.google.com/maps/documentation/timezone/overview)

```php
// requests the time zone data for given location
$timezoneResult = $gmaps->timezone([25.381111, 83.021389]); // Sárnáth
```

### Nearby API

[Nearby API overview | Google for Developers](https://developers.google.com/maps/documentation/places/web-service/search)

```php
// requests the nearby points for given location
$nearbyResult = $gmaps->nearby('restaurant', [25.71874, 32.6574]); // in Luxor
```

### Find by Place API

[Find by Place API overview | Google for Developers](https://developers.google.com/maps/documentation/places/web-service/search)

```php
// requests the place points for given location by given place
$nearbyResult = $gmaps->findPlace('Champs Elysees', 'restaurant', ['name', 'current_opening_hours']);
```

### Find by Text API

[Find by Text API overview | Google for Developers](https://developers.google.com/maps/documentation/places/web-service/search)

```php
// requests the place points for given location by given text
$nearbyResult = $gmaps->findText('Sagrada Familia', 350, [], 3, 0, true);
```

### Place details API

[Place details API overview | Google for Developers](https://developers.google.com/maps/documentation/places/web-service/search)

```php
// requests the details about place point
$nearbyResult = $gmaps->placeDetails('ChIJN1t_tDeuEmsRUsoyG83frY4', ['name', 'current_opening_hours']);
```


[Google Maps API Web Services]: https://developers.google.com/maps/documentation/webservices/
[Routes API]: https://developers.google.com/maps/documentation/routes
[Directions API]: https://developers.google.com/maps/documentation/directions/
[api-key]: https://developers.google.com/maps/documentation/directions/get-api-key#before-you-begin
[directions-key]: https://developers.google.com/maps/documentation/directions/get-api-key#key
[directions-client-id]: https://developers.google.com/maps/documentation/directions/get-api-key#client-id
[Distance Matrix API]: https://developers.google.com/maps/documentation/distancematrix/
[Elevation API]: https://developers.google.com/maps/documentation/elevation/
[Geocoding API]: https://developers.google.com/maps/documentation/geocoding/
[Geolocation API]: https://developers.google.com/maps/documentation/geolocation/
[Nearby API]: https://developers.google.com/maps/documentation/places/web-service/search-nearby/
[Time Zone API]: https://developers.google.com/maps/documentation/timezone/
[Find by Place API]: https://developers.google.com/maps/documentation/places/web-service/search-find-place
[Find by Text API]: https://developers.google.com/maps/documentation/places/web-service/search-text
[Place details API]: https://developers.google.com/maps/documentation/places/web-service/details
[Roads API]: https://developers.google.com/maps/documentation/roads/
[Places API]: https://developers.google.com/places/
