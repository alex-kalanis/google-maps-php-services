<?php

namespace tests\ServiceTests;


use tests\CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote\Headers;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;
use ReflectionException;


class FactoryTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     * @throws ReflectionException
     */
    public function testServiceOk(): void
    {
        $this->assertNotEmpty($this->getLib()->getService('directions'));
    }

    /**
     * @throws ServiceException
     * @throws ReflectionException
     */
    public function testServiceOkPassedTarget(): void
    {
        $conf = new ClientConfig('test');
        $request = new \tests\XRequest();
        $apiAuth = new Headers\ApiAuth($conf);
        $lang = new Headers\Language($conf);
        $this->assertNotEmpty(
            (new XFactory($request, $apiAuth, $lang, ))
                ->getService(new Services\Directions($request, $apiAuth, $lang, ))
        );
    }

    /**
     * @throws ServiceException
     * @throws ReflectionException
     */
    public function testServiceFailNoTarget(): void
    {
        $this->expectExceptionMessage('Call to undefined service method *unknown one*');
        $this->expectException(ServiceException::class);
        $this->getLib()->getService('unknown one');
    }

    /**
     * @throws ServiceException
     * @throws ReflectionException
     */
    public function testServiceFailWrongTarget(): void
    {
        $this->expectExceptionMessage('Service *tests\ServiceTests\XClass* is not an instance of \kalanis\google_maps\Services\AbstractService');
        $this->expectException(ServiceException::class);
        $this->getLib()->getService('unusable');
    }

    /**
     * @throws ServiceException
     * @throws ReflectionException
     */
    public function testServiceFailWrongServicePassed(): void
    {
        $this->expectExceptionMessage('Service *tests\ServiceTests\XClass* is not an instance of \kalanis\google_maps\Services\AbstractService');
        $this->expectException(ServiceException::class);
        $this->getLib()->getService(new XClass([]));
    }

    protected function getLib(): Services\ServiceFactory
    {
        $conf = new ClientConfig('test');
        return new XFactory(
            new \tests\XRequest(),
            new Headers\ApiAuth($conf),
            new Headers\Language($conf)
        );
    }
}
