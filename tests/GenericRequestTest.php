<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2018, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace UaRequestTest;

use PHPUnit\Framework\TestCase;
use UaRequest\Constants;
use UaRequest\GenericRequest;
use UaRequest\GenericRequestFactory;
use Zend\Diactoros\ServerRequestFactory;

final class GenericRequestTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstruct(): void
    {
        $userAgent = 'testUA';
        $browserUa = 'testBrowserUA';
        $deviceUa  = 'testDeviceUA';
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
            Constants::HEADER_DEVICE_UA      => $deviceUa,
            Constants::HEADER_UCBROWSER_UA   => $browserUa,
        ];

        $expectedHeaders = [
            'user-agent'          => $userAgent,
            'x-device-user-agent' => $deviceUa,
            'x-ucbrowser-ua'      => $browserUa,
        ];

        $object = new GenericRequest(ServerRequestFactory::fromGlobals($headers));

        self::assertSame($expectedHeaders, $object->getHeaders());
        self::assertSame($browserUa, $object->getBrowserUserAgent());
        self::assertSame($deviceUa, $object->getDeviceUserAgent());
    }

    /**
     * @return void
     */
    public function testToarray(): void
    {
        $userAgent = 'testUA';
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers));
        $array    = $original->getHeaders();
        $object   = (new GenericRequestFactory())->createRequestFromArray($array);

        self::assertEquals($original, $object);
    }

    /**
     * @return void
     */
    public function testToarraySimple(): void
    {
        $userAgent = 'testUA';
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers));
        $array    = $original->getHeaders();

        self::assertEquals(['user-agent' => $userAgent], $array);
    }

    /**
     * @return void
     */
    public function testForDevice(): void
    {
        $userAgent = 'testUA';
        $headers   = [
            Constants::HEADER_UCBROWSER_UA => $userAgent,
        ];

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers));
        $ua       = $original->getDeviceUserAgent();

        self::assertEquals('', $ua);
    }

    /**
     * @return void
     */
    public function testForDevice2(): void
    {
        $userAgent  = 'testUA';
        $userAgent2 = 'testUA2';
        $headers    = [
            Constants::HEADER_UCBROWSER_UA   => $userAgent,
            Constants::HEADER_HTTP_USERAGENT => $userAgent2,
        ];

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers));
        $ua       = $original->getDeviceUserAgent();

        self::assertEquals($userAgent2, $ua);
    }

    /**
     * @return void
     */
    public function testForBrowser(): void
    {
        $userAgent = 'testUA';
        $headers   = [
            Constants::HEADER_DEVICE_UA => $userAgent,
        ];

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers));
        $ua       = $original->getBrowserUserAgent();

        self::assertEquals('', $ua);
    }

    /**
     * @return void
     */
    public function testForBrowser2(): void
    {
        $userAgent  = 'testUA';
        $userAgent2 = 'testUA2';
        $headers    = [
            Constants::HEADER_DEVICE_UA    => $userAgent,
            Constants::HEADER_UCBROWSER_UA => $userAgent2,
        ];

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers));
        $ua       = $original->getBrowserUserAgent();

        self::assertEquals($userAgent2, $ua);
    }

    /**
     * @return void
     */
    public function testGetFilteredHeaders(): void
    {
        $userAgent       = 'testUA';
        $expectedHeaders = [
            'x-device-user-agent' => $userAgent,
        ];
        $headers = [
            Constants::HEADER_DEVICE_UA => $userAgent,
            'via'                       => 'test',
        ];

        $original      = new GenericRequest(ServerRequestFactory::fromGlobals($headers));
        $resultHeaders = $original->getFilteredHeaders();

        self::assertEquals($expectedHeaders, $resultHeaders);
    }
}
