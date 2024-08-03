<?php

namespace ServiceTests;


use CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class GeocodingTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testServiceGeocode(): void
    {
        $data = $this->getLib()->geocode('somewhere in the land');
        $this->assertEquals('https://maps.googleapis.com/maps/api/geocode/json?key=test&address=somewhere%20in%20the%20land', strval($data->getUri()));
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceReverse(): void
    {
        $data = $this->getLib()->reverseGeocode('you do not know where');
        $this->assertEquals('https://maps.googleapis.com/maps/api/geocode/json?key=test&place_id=you%20do%20not%20know%20where', strval($data->getUri()));
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceLatLngIndexed(): void
    {
        $data = $this->getLib()->reverseGeocode([10.7, 11.4]);
        $this->assertEquals('https://maps.googleapis.com/maps/api/geocode/json?key=test&latlng=10.70000000%2C11.40000000', strval($data->getUri()));
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceLatLngNamed(): void
    {
        $data = $this->getLib()->reverseGeocode(['lat' => 20.5, 'lng' => 22.8]);
        $this->assertEquals('https://maps.googleapis.com/maps/api/geocode/json?key=test&latlng=20.50000000%2C22.80000000', strval($data->getUri()));
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailWrongTarget(): void
    {
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(ServiceException::class);
        $this->getLib()->reverseGeocode(['foo' => 'bar']);
    }

    protected function getLib(): Services\Geocoding
    {
        $conf = ClientConfig::init('test');
        return new Services\Geocoding(new \XRequest(), new Remote\Headers\ApiAuth($conf), new Remote\Headers\Language($conf));
    }
}
