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
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $object = new GenericRequest($header, $userAgent, null, false, $browserUa, $deviceUa);

        self::assertSame($userAgent, $object->getUserAgent());
        self::assertSame($header, $object->getRequest());
        self::assertFalse($object->isXhtmlDevice());
        self::assertNull($object->getUserAgentProfile());
        self::assertSame($browserUa, $object->getBrowserUserAgent());
        self::assertSame($deviceUa, $object->getDeviceUserAgent());
        self::assertSame(hash('sha512', $userAgent), $object->getId());

        self::assertSame($userAgent, $object->getOriginalHeader(Constants::HEADER_HTTP_USERAGENT));
        self::assertNull($object->getOriginalHeader(Constants::HEADER_DEVICE_STOCK_UA));
    }

    public function testSerialize()
    {
        $userAgent = 'testUA';
        $browserUa = 'testBrowserUA';
        $deviceUa  = 'testDeviceUA';
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $original   = new GenericRequest($header, $userAgent, null, false, $browserUa, $deviceUa);
        $serialized = serialize($original);
        $object     = unserialize($serialized);

        self::assertEquals($original, $object);
    }

    public function testToarray()
    {
        $userAgent = 'testUA';
        $browserUa = 'testBrowserUA';
        $deviceUa  = 'testDeviceUA';
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $original   = new GenericRequest($header, $userAgent, null, false, $browserUa, $deviceUa);
        $array      = $original->toArray();
        $object     = (new GenericRequestFactory())->fromArray($array);

        self::assertEquals($original, $object);
    }

    public function testTojson()
    {
        $userAgent = 'testUA';
        $browserUa = 'testBrowserUA';
        $deviceUa  = 'testDeviceUA';
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $original   = new GenericRequest($header, $userAgent, null, false, $browserUa, $deviceUa);
        $json       = $original->toJson();
        $object     = (new GenericRequestFactory())->fromJson($json);

        self::assertEquals($original, $object);
    }
}
