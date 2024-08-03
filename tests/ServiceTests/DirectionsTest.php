<?php

namespace ServiceTests;


use CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class DirectionsTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testService(): void
    {
        $conf = ClientConfig::init('test');
        $lib = new Services\Directions(new \XRequest(), new Remote\Headers\ApiAuth($conf), new Remote\Headers\Language($conf));
        $data = $lib->directions('you do not know where', 'you do not want to know');
        $this->assertEquals('https://maps.googleapis.com/maps/api/directions/json?key=test&origin=you%20do%20not%20know%20where&destination=you%20do%20not%20want%20to%20know', $data->getRequestTarget());
        $this->assertEquals('', $data->getBody());
    }
}
