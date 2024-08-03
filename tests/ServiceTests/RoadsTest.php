<?php

namespace ServiceTests;


use CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class RoadsTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $data = $this->getLib()->snapToRoads('foo', ['interpolate' => true]);
        $this->assertEquals('https://roads.googleapis.com/v1/snapToRoads?key=test&interpolate=true&path=foo', strval($data->getUri()));
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFields(): void
    {
        $data = $this->getLib()->snapToRoads([[123.456, 789,123], [456.789, 123.456], [789.123, 456,789]], ['interpolate' => false]);
        $this->assertEquals('https://roads.googleapis.com/v1/snapToRoads?key=test&path=123.456%2C789%2C123%7C456.789%2C123.456%7C789.123%2C456%2C789', strval($data->getUri()));
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailWrongPath(): void
    {
        $this->expectExceptionMessage('Unknown path format. Pass array of arrays of floats or the string itself.');
        $this->expectException(ServiceException::class);
        $this->getLib()->snapToRoads(null, ['foo' => 'bar']);
    }

    protected function getLib(): Services\Roads
    {
        $conf = ClientConfig::init('test');
        return new Services\Roads(new \XRequest(), new Remote\Headers\ApiAuth($conf), new Remote\Headers\Language($conf));
    }
}
