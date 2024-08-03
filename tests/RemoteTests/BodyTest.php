<?php

namespace RemoteTests;


use CommonTestClass;
use kalanis\google_maps\Remote\Body;
use RuntimeException;


class BodyTest extends CommonTestClass
{
    public function testBodyNotSet(): void
    {
        $lib = new Body();
        $this->assertEmpty((string) $lib);
        $this->assertEmpty($lib->getContents());
        $this->assertEquals(0, $lib->getSize());
        $lib->close();
        $lib->detach();
        $lib->rewind();
        $this->assertTrue($lib->isReadable());
        $this->assertTrue($lib->isWritable());
        $this->assertTrue($lib->isSeekable());
        $this->assertTrue($lib->eof());
    }

    public function testBodySet(): void
    {
        $lib = new Body('prilis zlutoucky kun pel dabelske ody');
        $this->assertEquals('prilis zlutoucky kun pel dabelske ody', (string) $lib);
        $this->assertEquals('prilis zlutoucky kun pel dabelske ody', $lib->getContents());
        $this->assertEquals(37, $lib->getSize());
        $lib->close();
        $lib->detach();
        $lib->rewind();
        $this->assertFalse($lib->eof());
        $this->assertEquals(0, $lib->tell());
        $this->assertNotEmpty($lib->getMetadata());
        $this->assertNull($lib->getMetadata('unknown'));
        $this->assertNotEmpty($lib->getMetadata('seekable'));
    }

    public function testBodyReadWrite(): void
    {
        $lib = new Body('dummy');
        $this->assertEquals('dummy', $lib->getContents());
        $this->assertEquals(5, $lib->getSize());
        $this->assertFalse($lib->eof());
        $lib->write('prilis zlutoucky kun pel dabelske ody');
        $this->assertEquals(42, $lib->getSize());
        $lib->seek(42);
        $this->assertTrue($lib->eof());
        $lib->seek(999, SEEK_END);
        $this->assertEquals(42, $lib->tell());
        $lib->seek(-13, SEEK_CUR);
        $this->assertEquals(29, $lib->tell());
        $lib->seek(9);
        $this->assertEquals(9, $lib->tell());
        $this->assertEquals('is zlutoucky ku', $lib->read(15));
        $this->assertEquals(24, $lib->tell());
        $this->assertFalse($lib->eof());
        $this->assertEquals('n pel dabelske ody', $lib->read(60));
        $this->assertEquals(42, $lib->tell());
        $this->assertTrue($lib->eof());
    }

    /**
     * @throws RuntimeException
     */
    public function testBodySeekWhat(): void
    {
        $lib = new Body('dummy');
        $this->expectExceptionMessage('Bad seek mode!');
        $this->expectException(RuntimeException::class);
        $lib->seek(0, 999);
    }
}
