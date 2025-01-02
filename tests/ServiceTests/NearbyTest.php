<?php

namespace tests\ServiceTests;


use tests\CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class NearbyTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $data = $this->getLib()->nearby('foo', [], 10.2, 'bar');
        $this->assertEquals('https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=test&keyword=foo&radius=10.2&type=bar', strval($data->getUri()));
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @param string $result
     * @param array<string|int, string> $latLng
     * @throws ServiceException
     * @dataProvider serviceLatLngProvider
     */
    public function testServiceLatLng(string $result, array $latLng): void
    {
        $data = $this->getLib()->nearby('', $latLng);
        $this->assertEquals($result, strval($data->getUri()));
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    public static function serviceLatLngProvider(): array
    {
        return [
            ['https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=test&latlng=10.70000000%2C11.40000000', [10.7, 11.4]],
            ['https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=test&latlng=20.50000000%2C22.80000000', ['lat' => 20.5, 'lng' => 22.8]],
        ];
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailNoTarget(): void
    {
        $this->expectExceptionMessage('You must set where to look!');
        $this->expectException(ServiceException::class);
        $this->getLib()->nearby('', []);
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailWrongTarget(): void
    {
        $this->expectExceptionMessage('Passed invalid values into coordinates!');
        $this->expectException(ServiceException::class);
        $this->getLib()->nearby('', ['foo' => 'bar']);
    }

    protected function getLib(): Services\Nearby
    {
        $conf = ClientConfig::init('test');
        return new Services\Nearby(new \tests\XRequest(), new Remote\Headers\ApiAuth($conf), new Remote\Headers\Language($conf));
    }
}
