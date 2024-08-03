<?php

namespace RemoteTests;


use CommonTestClass;
use kalanis\google_maps\Remote\Response;


class ResponseTest extends CommonTestClass
{
    public function testAllOk(): void
    {
        $lib = new Response();
        $this->assertEquals([
            'a',
            'b',
        ], $lib->process(new \XResponse(200, '{"results": ["a", "b"]}'), true));
        $this->assertEquals(['results' => [
            'a',
            'b',
        ]], $lib->process(new \XResponse(200, '{"results": ["a", "b"]}'), false));
    }

    public function testErrorDirect(): void
    {
        $lib = new Response();
        $this->assertEquals('Direct message', $lib->process(new \XResponse(200, 'Direct message'), true));
    }

    public function testErrorCoded1(): void
    {
        $lib = new Response();
        $this->assertEquals('Direct message', $lib->process(new \XResponse(400, 'Direct message'), true));
    }

    public function testErrorCoded2(): void
    {
        $lib = new Response();
        $this->assertEquals(['fail' => 'Unable to show'], $lib->process(new \XResponse(400, '{"fail": "Unable to show"}'), true));
    }

    public function testErrorMessage(): void
    {
        $lib = new Response();
        $this->assertEquals(['error_message' => 'Unable to run'], $lib->process(new \XResponse(400, '{"error_message": "Unable to run"}'), true));
    }
}
