<?php

namespace ServiceTests;


use CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class PlaceDetailsTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $data = $this->getLib()->placeDetails('foo', [], 'Greece', false, 'Newest');
        $this->assertEquals('https://maps.googleapis.com/maps/api/place/details/json?key=test&place_id=foo&region=gr&reviews_no_translations=true&reviews_sort=newest', $data->getRequestTarget());
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFields(): void
    {
        $data = $this->getLib()->placeDetails('foo', ['rating', 'witchcraft', 'photo', 'issues']);
        $this->assertEquals('https://maps.googleapis.com/maps/api/place/details/json?key=test&place_id=foo&fields=photo%2Crating', $data->getRequestTarget());
        $this->assertNotEmpty($data->getBody());
        $this->assertEmpty($data->getBody()->getContents());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceFailNoTarget(): void
    {
        $this->expectExceptionMessage('You must set where to look!');
        $this->expectException(ServiceException::class);
        $this->getLib()->placeDetails('');
    }

    protected function getLib(): Services\PlaceDetails
    {
        $conf = ClientConfig::init('test');
        return new Services\PlaceDetails(new \XRequest(), new Remote\Headers\ApiAuth($conf), new Remote\Headers\Language($conf));
    }
}
