<?php

namespace ServiceTests;


use CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class FindPlaceTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $data = $this->getLib()->findPlace('foo', 'bar');
        $this->assertEquals('https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key=test&input=foo&inputtype=bar', strval($data->getUri()));
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFields(): void
    {
        $data = $this->getLib()->findPlace('foo', 'bar', ['rating', 'witchcraft', 'photo', 'issues']);
        $this->assertEquals('https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key=test&input=foo&inputtype=bar&fields=photo%2Crating', strval($data->getUri()));
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @param string $result
     * @param array<string|int, string> $bias
     * @throws ServiceException
     * @dataProvider biasProvider
     */
    public function testServiceBias(string $result, array $bias): void
    {
        $data = $this->getLib()->findPlace('foo', 'bar', [], $bias);
        $this->assertEquals($result, strval($data->getUri()));
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    public static function biasProvider(): array
    {
        return [
            ['https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key=test&input=foo&inputtype=bar&locationbias=ipbias', []],
            ['https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key=test&input=foo&inputtype=bar&locationbias=circle%3A70.00%4010.700000%2C11.400000', [10.7, 11.4, 70]],
            ['https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key=test&input=foo&inputtype=bar&locationbias=circle%3A6.30%4020.500000%2C22.800000', ['lat' => 20.5, 'lng' => 22.8, 'rad' => 6.3]],
            ['https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key=test&input=foo&inputtype=bar&locationbias=rectangle%3A10.700000%2C11.400000%7C-9.700000%2C-3.200000', [10.7, 11.4, -9.7, -3.2]],
            ['https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key=test&input=foo&inputtype=bar&locationbias=rectangle%3A15.000000%2C-5.100000%7C20.500000%2C22.800000', ['n' => 20.5, 'e' => 22.8, 's' => 15.0, 'w' => -5.1, ]],
        ];
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailNoTarget(): void
    {
        $this->expectExceptionMessage('You must set where to look!');
        $this->expectException(ServiceException::class);
        $this->getLib()->findPlace('', 'foo');
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailWrongTarget(): void
    {
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(ServiceException::class);
        $this->getLib()->findPlace('foo', 'bar', [], ['foo' => 'bar']);
    }

    protected function getLib(): Services\FindPlace
    {
        $conf = ClientConfig::init('test');
        return new Services\FindPlace(new \XRequest(), new Remote\Headers\ApiAuth($conf), new Remote\Headers\Language($conf));
    }
}
