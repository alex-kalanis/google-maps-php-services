<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\ServiceException;


class ClientConfigTest extends CommonTestClass
{
    public function testByNew(): void
    {
        $lib = new ClientConfig('wanabe key');
        $this->assertEquals('wanabe key', $lib->getApiKey());
        $this->assertNull($lib->getLanguage());
        $lib->setLanguage('foo');
        $this->assertEquals('foo', $lib->getLanguage());
        $lib->setLanguage(null);
        $this->assertNull($lib->getLanguage());
    }

    /**
     * @throws ServiceException
     */
    public function testInitString(): void
    {
        $lib = ClientConfig::init('wanabe key');
        $this->assertNull($lib->getLanguage());
        $this->assertEquals('wanabe key', $lib->getApiKey());
    }

    /**
     * @throws ServiceException
     */
    public function testInitArray1(): void
    {
        $lib = ClientConfig::init(['key' => 'wanabe key']);
        $this->assertNull($lib->getLanguage());
        $this->assertEquals('wanabe key', $lib->getApiKey());
    }

    /**
     * @throws ServiceException
     */
    public function testInitArray2(): void
    {
        $lib = ClientConfig::init(['key' => 'wanabe key', 'language' => 'baz']);
        $this->assertEquals('wanabe key', $lib->getApiKey());
        $this->assertEquals('baz', $lib->getLanguage());
    }

    public function testInitArrayFail(): void
    {
        $this->expectExceptionMessage('Unable to set Client credential due to your wrong params');
        $this->expectException(ServiceException::class);
        ClientConfig::init(['foo' => 'bar', 'baz' => 'oof']);
    }
}
