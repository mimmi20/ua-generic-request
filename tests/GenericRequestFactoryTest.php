<?php

namespace WurflTest\Request;

use Wurfl\Request\Constants;
use Wurfl\Request\GenericRequest;
use Wurfl\Request\GenericRequestFactory;

/**
 * test case
 */
class GenericRequestFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Wurfl\Request\GenericRequestFactory
     */
    private $object = null;

    public function setUp()
    {
        $this->object = new GenericRequestFactory();
    }

    public function testCreateRequest()
    {
        $userAgent = 'testUA';
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $expected = new GenericRequest($header, $userAgent, null, false);

        $result = $this->object->createRequest($header, false);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertEquals($expected, $result);
        self::assertNull($result->getUserAgentProfile());
    }

    public function testCreateRequestForUserAgent()
    {
        $userAgent = 'testUA';
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $expected = new GenericRequest($header, $userAgent, null, false);

        $result = $this->object->createRequestForUserAgent($userAgent);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertEquals($expected, $result);
        self::assertNull($result->getUserAgentProfile());
    }

    public function testToarray()
    {
        $result = $this->object->fromArray([]);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertSame('', $result->getUserAgent());
    }

    public function testCreateRequestFromParam()
    {
        $userAgent = 'testUA';
        $profile   = 'testProfile';
        $header    = [
            Constants::UA                 => $userAgent,
            Constants::HEADER_WAP_PROFILE => $profile,
        ];

        $expected = new GenericRequest($header, $userAgent, $profile, false);

        $result = $this->object->createRequest($header, false);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertEquals($expected, $result);
        self::assertSame($userAgent, $result->getUserAgent());
        self::assertSame($profile, $result->getUserAgentProfile());
    }

    public function testCreateRequestFromHeader()
    {
        $userAgent = 'testUA';
        $deviceUa  = 'testDeviceUa';
        $profile   = 'testProfile';
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
            Constants::HEADER_DEVICE_UA      => $deviceUa,
            Constants::HEADER_PROFILE        => $profile,
        ];

        $expected = new GenericRequest($header, true);

        $result = $this->object->createRequest($header, true);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertEquals($expected, $result);
        self::assertSame($deviceUa, $result->getUserAgent());
        self::assertSame($userAgent, $result->getBrowserUserAgent());
        self::assertSame($deviceUa, $result->getDeviceUserAgent());
        self::assertSame($profile, $result->getUserAgentProfile());
    }
}
