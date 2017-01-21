<?php

namespace WurflTest\Request;

use Wurfl\Request\Constants;
use Wurfl\Request\GenericRequest;
use Wurfl\Request\GenericRequestFactory;

/**
 * test case
 */
class GenericRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $userAgent = 'testUA';
        $browserUa = 'testBrowserUA';
        $deviceUa  = 'testDeviceUA';
        $profile   = 'testProfile';
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
            Constants::HEADER_DEVICE_UA      => $deviceUa,
            Constants::HEADER_UCBROWSER_UA   => $browserUa,
            Constants::HEADER_PROFILE        => $profile,
        ];

        $object = new GenericRequest($header, false);

        self::assertSame($userAgent, $object->getUserAgent());
        self::assertSame($header, $object->getRequest());
        self::assertFalse($object->isXhtmlDevice());
        self::assertSame($profile, $object->getUserAgentProfile());
        self::assertSame($browserUa, $object->getBrowserUserAgent());
        self::assertSame($deviceUa, $object->getDeviceUserAgent());
        self::assertSame(hash('sha512', $userAgent), $object->getId());

        self::assertSame($userAgent, $object->getOriginalHeader(Constants::HEADER_HTTP_USERAGENT));
        self::assertNull($object->getOriginalHeader(Constants::HEADER_DEVICE_STOCK_UA));
    }

    public function testSerialize()
    {
        $userAgent = 'testUA';
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $original   = new GenericRequest($header);
        $serialized = serialize($original);
        $object     = unserialize($serialized);

        self::assertEquals($original, $object);
    }

    public function testToarray()
    {
        $userAgent = 'testUA';
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $original   = new GenericRequest($header);
        $array      = $original->toArray();
        $object     = (new GenericRequestFactory())->fromArray($array);

        self::assertEquals($original, $object);
    }

    public function testTojson()
    {
        $userAgent = 'testUA';
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $original   = new GenericRequest($header);
        $json       = $original->toJson();
        $object     = (new GenericRequestFactory())->fromJson($json);

        self::assertEquals($original, $object);
    }
}
