<?php

namespace ServiceTests;


use CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class TimezoneTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testServiceString(): void
    {
        $data = $this->getLib()->timezone('10.1, 20.2', 1234567890);
        $this->assertEquals('https://maps.googleapis.com/maps/api/timezone/json?key=test&location=10.1%2C%2020.2&timestamp=1234567890', strval($data->getUri()));
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceLatLngIndexed(): void
    {
        $data = $this->getLib()->timezone([10.7, 11.4], 1234567);
        $this->assertEquals('https://maps.googleapis.com/maps/api/timezone/json?key=test&location=10.70000000%2C11.40000000&timestamp=1234567', strval($data->getUri()));
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceLatLngNamed(): void
    {
        $data = $this->getLib()->timezone(['lat' => 20.5, 'lng' => 22.8], 1234567);
        $this->assertEquals('https://maps.googleapis.com/maps/api/timezone/json?key=test&location=20.50000000%2C22.80000000&timestamp=1234567', strval($data->getUri()));
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
        $this->getLib()->timezone(['foo' => 'bar']);
    }

    protected function getLib(): Services\Timezone
    {
        $conf = ClientConfig::init('test');
        return new Services\Timezone(new \XRequest(), new Remote\Headers\ApiAuth($conf), new Remote\Headers\Language($conf));
    }
}
