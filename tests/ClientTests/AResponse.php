<?php

namespace ClientTests;


use CommonTestClass;


abstract class AResponse extends CommonTestClass
{
    const DELIMITER = "\r\n";

    public static function responseProvider(): array
    {
        return [
            [static::getResponseSimple(), 900, 'abcdefghijkl'],
            [static::getResponseEmpty(), 901, ''],
            [static::getResponseHeaders(), 902, 'abcdefghijkl'],
        ];
    }

    protected static function getResponseSimple()
    {
        return 'HTTP/0.1 900 KO' . self::DELIMITER . self::DELIMITER . 'abcdefghijkl';
    }

    protected static function getResponseEmpty()
    {
        return 'HTTP/0.1 901 KO';
    }

    protected static function getResponseHeaders()
    {
        return 'HTTP/0.1 902 KO' . self::DELIMITER
            . 'Server: PhpUnit/9.3.0' . self::DELIMITER
            . 'Content-Length: 12' . self::DELIMITER
            . 'Content-Type: text/plain' . self::DELIMITER
            . 'Connection: Closed' . self::DELIMITER
            . self::DELIMITER
            . 'abcdefghijkl'
            ;
    }

}
