<?php

namespace ServiceTests;


use CommonTestClass;
use kalanis\google_maps\ApiAuth;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class DirectionsTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new Services\Directions(new ApiAuth('test'));
        $this->assertEquals('https://maps.googleapis.com/maps/api/directions/json', $lib->getPath());
        $this->assertEquals([
            'origin' => 'you do not know where',
            'destination' => 'you do not want to know',
            'key' => 'test',
        ], $lib->directions('you do not know where', 'you do not want to know'));
    }
}
