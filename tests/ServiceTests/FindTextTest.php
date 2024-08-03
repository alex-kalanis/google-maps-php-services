<?php

namespace ServiceTests;


use CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class FindTextTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $data = $this->getLib()->findText('foo', 5, [], 8, -3, true, 'Italy', 'stadium');
        $this->assertEquals('https://maps.googleapis.com/maps/api/place/textsearch/json?key=test&query=foo&radius=5.00&maxprice=8&minprice=-3&opennow=true&region=it&type=stadium', $data->getRequestTarget());
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @param string $result
     * @param float<0, 50000> $radius
     * @param array<string|int, string> $location
     * @throws ServiceException
     * @dataProvider biasProvider
     */
    public function testServiceBias(string $result, float $radius, array $location): void
    {
        $data = $this->getLib()->findText('foo', $radius, $location);
        $this->assertEquals($result, $data->getRequestTarget());
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    public static function biasProvider(): array
    {
        return [
            ['https://maps.googleapis.com/maps/api/place/textsearch/json?key=test&query=foo&radius=70.00&location=10.70000000%2C11.40000000', 70, [10.7, 11.4]],
            ['https://maps.googleapis.com/maps/api/place/textsearch/json?key=test&query=foo&radius=6.30&location=20.50000000%2C22.80000000', 6.3, ['lat' => 20.5, 'lng' => 22.8]],
        ];
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailNoTarget(): void
    {
        $this->expectExceptionMessage('You must set where to look!');
        $this->expectException(ServiceException::class);
        $this->getLib()->findText('', 51);
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailWrongCoords(): void
    {
        $this->expectExceptionMessage('Passed invalid values into coordinates! You must use either array with lat and lng and rad or 0, 1, 2 and 3 keys.');
        $this->expectException(ServiceException::class);
        $this->getLib()->findText('foo', 5, ['bar' => 'baz']);
    }

    public function getLib(): Services\FindText
    {
        $conf = ClientConfig::init('test');
        return new Services\FindText(new \XRequest(), new Remote\Headers\ApiAuth($conf), new Remote\Headers\Language($conf));
    }
}
