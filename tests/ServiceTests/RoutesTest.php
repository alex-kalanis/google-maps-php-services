<?php

namespace ServiceTests;


use CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class RoutesTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testServiceRoute(): void
    {
        $data = $this->getLib()->computeRoutes([10.7, 11.4, 'foo' => 'bar',], []);
        $this->assertEquals('POST', $data->getMethod());
        $this->assertEquals('https://routes.googleapis.com/directions/v2:computeRoutes', strval($data->getUri()));
        $this->assertEquals([
            'X-Goog-FieldMask' => ['routes.duration,routes.distanceMeters,routes.legs,geocodingResults'],
            'X-Goog-Api-Key' => ['test'],
        ], $data->getHeaders());
        $this->assertEquals('{"origin":{"0":10.7,"1":11.4,"foo":"bar"},"destination":[]}', $data->getBody()->getContents());
    }

    /**
     * @throws ServiceException
     */
    public function testServiceRouteLang(): void
    {
        $data = $this->getLib('hk')->computeRoutes(['foo' => 'bar',], [10.7, 11.4,]);
        $this->assertEquals('POST', $data->getMethod());
        $this->assertEquals('https://routes.googleapis.com/directions/v2:computeRoutes', strval($data->getUri()));
        $this->assertEquals([
            'X-Goog-FieldMask' => ['routes.duration,routes.distanceMeters,routes.legs,geocodingResults'],
            'X-Goog-Api-Key' => ['test'],
        ], $data->getHeaders());
        $this->assertEquals('{"origin":{"foo":"bar"},"destination":[10.7,11.4],"languageCode":"hk"}', $data->getBody()->getContents());
    }

    protected function getLib(?string $lang = null): Services\Routes
    {
        $conf = new ClientConfig('test', $lang);
        return new Services\Routes(new \XRequest(), new Remote\Headers\ApiAuth($conf), new Remote\Headers\Language($conf));
    }
}
