<?php

namespace tests\RemoteTests;


use tests\CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote\Headers\ApiAuth;
use kalanis\google_maps\ServiceException;


class ApiAuthTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testServicePassStringKey(): void
    {
        $lib = new ApiAuth(ClientConfig::init('wanabe key'));
        $this->assertEquals('wanabe key', $lib->getKey());
        $this->assertEquals([
            'key' => 'wanabe key',
        ], $lib->getAuthParams());
    }
}
