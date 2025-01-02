<?php

namespace tests\ServiceTests;


use tests\CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class DistanceTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $conf = ClientConfig::init('test');
        $lib = new Services\DistanceMatrix(new \tests\XRequest(), new Remote\Headers\ApiAuth($conf), new Remote\Headers\Language($conf));
        $data = $lib->distanceMatrix('you do not know where', 'you do not want to know');
        $this->assertEquals('https://maps.googleapis.com/maps/api/distancematrix/json?key=test&origins=you%20do%20not%20know%20where&destinations=you%20do%20not%20want%20to%20know', strval($data->getUri()));
        $this->assertEquals('', $data->getBody());
    }
}
