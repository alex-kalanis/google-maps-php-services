<?php

namespace RemoteTests;


use CommonTestClass;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote\Headers\Language;
use kalanis\google_maps\ServiceException;


class LanguageTest extends CommonTestClass
{
    /**
     * @throws ServiceException
     */
    public function testLangNotSet(): void
    {
        $lib = new Language(ClientConfig::init('wanabe key'));
        $this->assertNull($lib->getLanguage());
        $this->assertEquals([], $lib->getToQuery('foo'));
        $this->assertEquals([], $lib->getToQuery('get'));
        $this->assertEquals([], $lib->getToQuery('post'));
    }

    /**
     * @throws ServiceException
     */
    public function testLangSet(): void
    {
        $lib = new Language(new ClientConfig('wanabe key', 'pt-br'));
        $this->assertEquals('pt-br', $lib->getLanguage());
        $this->assertEquals([], $lib->getToQuery('foo'));
        $this->assertEquals(['language' => 'pt-br'], $lib->getToQuery('get'));
        $this->assertEquals([], $lib->getToQuery('post'));
    }
}
