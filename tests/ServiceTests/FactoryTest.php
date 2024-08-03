<?php

namespace ServiceTests;


use CommonTestClass;
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
        $this->expectExceptionMessage('Service *ServiceTests\XClass* is not an instance of \kalanis\google_maps\Services\AbstractService');
        $this->expectException(ServiceException::class);
        $this->getLib()->getService('unusable');
    }

    protected function getLib(): Services\ServiceFactory
    {
        $conf = new ClientConfig('test');
        return new XFactory(
            new \XRequest(),
            new Headers\ApiAuth($conf),
            new Headers\Language($conf)
        );
    }
}


class XFactory extends Services\ServiceFactory
{
    protected array $serviceMethodMap = [
        'directions' => Services\Directions::class,
        'unusable' => XClass::class,
    ];
}


class XClass extends \stdClass
{
    public function __construct($param)
    {
    }
}

