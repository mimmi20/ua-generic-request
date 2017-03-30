<?php
/**
 * This file is part of the wurfl-generic-request package.
 *
 * Copyright (c) 2015-2017, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace WurflTest\Request;

use Wurfl\Request\Constants;
use Wurfl\Request\GenericRequest;
use Wurfl\Request\GenericRequestFactory;

/**
 * test case
 */
class GenericRequestFactoryTest extends \PHPUnit\Framework\TestCase
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

        $expected = new GenericRequest($header, false);

        $result = $this->object->createRequest($header, false);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertEquals($expected, $result);
        self::assertSame($userAgent, $result->getUserAgent());
        self::assertSame('', $result->getUserAgentProfile());
    }

    public function testCreateRequestFromEmptyHeaders()
    {
        $header    = [];

        $expected = new GenericRequest($header, false);

        $result = $this->object->createRequest($header, false);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertEquals($expected, $result);
        self::assertSame('', $result->getUserAgent());
        self::assertSame('', $result->getUserAgentProfile());
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
        self::assertSame($userAgent, $result->getUserAgent());
        self::assertSame('', $result->getUserAgentProfile());
    }

    public function testToarray()
    {
        $result = $this->object->fromArray([]);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertSame('', $result->getUserAgent());
    }

    public function testToarrayWithUa()
    {
        $userAgent = 'testUA';
        $result    = $this->object->fromArray(['userAgent' => $userAgent]);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertSame($userAgent, $result->getUserAgent());
    }

    public function testToarrayWithUaInHeaderArray()
    {
        $userAgent = 'testUA';
        $result    = $this->object->fromArray(['headers' => [Constants::HEADER_HTTP_USERAGENT => $userAgent]]);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertSame($userAgent, $result->getUserAgent());
    }

    public function testToarrayWithUaInRequestArray()
    {
        $userAgent = 'testUA';
        $result    = $this->object->fromArray(['request' => [Constants::HEADER_HTTP_USERAGENT => $userAgent]]);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertSame($userAgent, $result->getUserAgent());
    }

    public function testToarrayWithUaSimple()
    {
        $userAgent = 'testUA';
        $result    = $this->object->fromArray([Constants::HEADER_HTTP_USERAGENT => $userAgent]);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertSame($userAgent, $result->getUserAgent());
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
            Constants::ACCEPT_HEADER_NAME    => Constants::ACCEPT_HEADER_XHTML_XML,
        ];

        $expected = new GenericRequest($header, true);

        $result = $this->object->createRequest($header, true);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertEquals($expected, $result);
        self::assertSame($deviceUa, $result->getUserAgent());
        self::assertSame($userAgent, $result->getBrowserUserAgent());
        self::assertSame($deviceUa, $result->getDeviceUserAgent());
        self::assertSame($profile, $result->getUserAgentProfile());
        self::assertTrue($result->isXhtmlDevice());
    }

    public function testCreateRequestFromOptHeader()
    {
        $userAgent = 'testUA';
        $deviceUa  = 'testDeviceUa';
        $profile   = 'testProfile';
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
            Constants::HEADER_DEVICE_UA      => $deviceUa,
            Constants::HEADER_OPT            => 'ns=01234',
            Constants::ACCEPT_HEADER_NAME    => 'irregular',
            '=01234-Profile'                 => $profile,
        ];

        $expected = new GenericRequest($header, true);

        $result = $this->object->createRequest($header, true);

        self::assertInstanceOf('\Wurfl\Request\GenericRequest', $result);
        self::assertEquals($expected, $result);
        self::assertSame($deviceUa, $result->getUserAgent());
        self::assertSame($userAgent, $result->getBrowserUserAgent());
        self::assertSame($deviceUa, $result->getDeviceUserAgent());
        self::assertSame($profile, $result->getUserAgentProfile());
        self::assertFalse($result->isXhtmlDevice());
    }
}
