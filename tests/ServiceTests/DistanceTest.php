<?php

namespace ServiceTests;


use CommonTestClass;
use kalanis\google_maps\ApiAuth;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class DistanceTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $lib = new Services\DistanceMatrix(new ApiAuth('test'));
        $this->assertEquals('https://maps.googleapis.com/maps/api/distancematrix/json', $lib->getPath());
        $this->assertEquals([
            'origins' => 'you do not know where',
            'destinations' => 'you do not want to know',
            'key' => 'test',
        ], $lib->distanceMatrix('you do not know where', 'you do not want to know'));
    }
}
