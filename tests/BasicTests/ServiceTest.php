<?php

namespace tests\BasicTests;


use tests\CommonTestClass;
use kalanis\google_maps\Client;
use kalanis\google_maps\ClientConfig;
use kalanis\google_maps\Remote\Headers\ApiAuth;
use kalanis\google_maps\Remote\Headers\Language;
use kalanis\google_maps\Remote\Response;
use kalanis\google_maps\ServiceException;
use kalanis\google_maps\Services;


class ServiceTest extends CommonTestClass
{
    public function testServiceFailDecode(): void
    {
        $conf = new ClientConfig('test');
        $lib = new Services(
            new Services\ServiceFactory(
                new \tests\XRequest(),
                new ApiAuth($conf),
                new Language($conf)
            ),
            new \tests\XMockedResponse(
                new \tests\XResponse(
                    200,
                    'dummy response from remote service',
                )
            ),
            new Response()
        );
        $this->assertEquals('dummy response from remote service', $lib->directions('foo', 'bar'));
    }

    public function testServiceFailCode(): void
    {
        $conf = new ClientConfig('test');
        $lib = new Services(
            new Services\ServiceFactory(
                new \tests\XRequest(),
                new ApiAuth($conf),
                new Language($conf)
            ),
            new \tests\XMockedResponse(
                new \tests\XResponse(
                    400,
                    '{"error": "Unable to show"}',
                )
            ),
            new Response()
        );
        $this->assertEquals([
            'error' => 'Unable to show',
        ], $lib->directions('foo', 'bar'));
    }

    public function testServiceFailMessage(): void
    {
        $conf = new ClientConfig('test');
        $lib = new Services(
            new Services\ServiceFactory(
                new \tests\XRequest(),
                new ApiAuth($conf),
                new Language($conf)
            ),
            new \tests\XMockedResponse(
                new \tests\XResponse(
                    200,
                    '{"error_message": "Unable to show"}',
                )
            ),
            new Response()
        );
        $this->assertEquals([
            'error_message' => 'Unable to show',
        ], $lib->directions('foo', 'bar'));
    }

    public function testServiceFailInstance(): void
    {
        $conf = new ClientConfig('test');
        $lib = new Services(
            new XFactory(
                new \tests\XRequest(),
                new ApiAuth($conf),
                new Language($conf)
            ),
            new \tests\XMockedResponse(
                new \tests\XResponse(
                    200,
                    '{"error_message": "Unable to show"}',
                )
            ),
            new Response()
        );
        $this->expectExceptionMessage('Call *tests\BasicTests\FailingReturn::dummy* cannot be used - returns *string*!');
        $this->expectException(ServiceException::class);
        $lib->dummy();
    }

    public function testServicePassWithoutResults(): void
    {
        $conf = new ClientConfig('test');
        $lib = new Services(
            new Services\ServiceFactory(
                new \tests\XRequest(),
                new ApiAuth($conf),
                new Language($conf)
            ),
            new \tests\XMockedResponse(
                new \tests\XResponse(
                    200,
                    '{"status": "Show now"}',
                )
            ),
            new Response()
        );
        $this->assertEquals([
            'status' => 'Show now',
        ], $lib->directions('foo', 'bar'));
    }

    public function testServicePassWithResults(): void
    {
        $conf = new ClientConfig('test');
        $lib = new Services(
            new Services\ServiceFactory(
                new \tests\XRequest(),
                new ApiAuth($conf),
                new Language($conf)
            ),
            new \tests\XMockedResponse(
                new \tests\XResponse(
                    200,
                    '{"results": ["a", "b"]}',
                )
            ),
            new Response()
        );
        $this->assertEquals([
            'a',
            'b',
        ], $lib->directions('foo', 'bar'));
    }

    public function testClientRun(): void
    {
        $lib = new Client(
            new \tests\XRequest(),
            new \tests\XMockedResponse( // mocked remote response which returns known values
                new \tests\XResponse(
                    200,
                    '{"results": ["a", "b"]}',
                )
            ),
            new ClientConfig('test'),
        );
        $lib->setLanguage('fr-fr');
        $this->assertEquals([
            'a',
            'b',
        ], $lib->directions('foo', 'bar'));
    }
}


class FailingReturn extends Services\AbstractService
{
    public function dummy(): string
    {
        return 'this is not a request';
    }
}


class XFactory extends Services\ServiceFactory
{
    protected array $serviceMethodMap = [
        'dummy' => FailingReturn::class,
    ];
}
