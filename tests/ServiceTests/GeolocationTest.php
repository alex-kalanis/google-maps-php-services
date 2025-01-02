<?php

namespace tests\ServiceTests;


use tests\CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class GeolocationTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $conf = ClientConfig::init('test');
        $lib = new Services\Geolocation(new \tests\XRequest(), new Remote\Headers\ApiAuth($conf), new Remote\Headers\Language($conf));

        $data = $lib->geolocate([10.7, 11.4, 'foo' => 'bar']);
        $this->assertEquals('POST', $data->getMethod());
        $this->assertEquals('https://www.googleapis.com/geolocation/v1/geolocate?key=test', strval($data->getUri()));
        $this->assertEquals('{"0":10.7,"1":11.4,"foo":"bar"}', $data->getBody()->getContents());
    }
}
