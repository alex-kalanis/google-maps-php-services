<?php

namespace ServiceTests;


use CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class ElevationTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = $this->getLib();
        $data = $lib->elevation('you do not know where');
        $this->assertEquals('https://maps.googleapis.com/maps/api/elevation/json?key=test&locations=you%20do%20not%20know%20where', $data->getRequestTarget());
        $this->assertEquals('', $data->getBody());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceLatLngIndexed(): void
    {
        $data = $this->getLib()->elevation([10.7, 11.4]);
        $this->assertEquals('https://maps.googleapis.com/maps/api/elevation/json?key=test&locations=10.70000000%2C11.40000000', $data->getRequestTarget());
        $this->assertEquals('', $data->getBody());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceLatLngNamed(): void
    {
        $data = $this->getLib()->elevation(['lat' => 20.5, 'lng' => 22.8]);
        $this->assertEquals('https://maps.googleapis.com/maps/api/elevation/json?key=test&locations=20.50000000%2C22.80000000', $data->getRequestTarget());
        $this->assertEquals('', $data->getBody());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailWrongTarget(): void
    {
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(ServiceException::class);
        $this->getLib()->elevation(['foo' => 'bar']);
    }

    protected function getLib(): Services\Elevation
    {
        $conf = ClientConfig::init('test');
        return new Services\Elevation(new \XRequest(), new Remote\Headers\ApiAuth($conf), new Remote\Headers\Language($conf));
    }
}
