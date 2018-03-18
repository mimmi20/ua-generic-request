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

class GenericRequestTest extends TestCase
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

        $object = new GenericRequest($headers);

        self::assertSame($headers, $object->getHeaders());
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

        $original = new GenericRequest($headers);
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

        $original = new GenericRequest($headers);
        $array    = $original->getHeaders();

        self::assertEquals($headers, $array);
    }

    /**
     * @return void
     */
    public function testForDevice(): void
    {
        $userAgent = 'testUA';
        $headers   = [
            Constants::HEADER_DEVICE_UA => $userAgent,
        ];

        $original = new GenericRequest($headers);
        $ua       = $original->getDeviceUserAgent();

        self::assertEquals($userAgent, $ua);
    }

    /**
     * @return void
     */
    public function testForBrowser(): void
    {
        $userAgent = 'testUA';
        $headers   = [
            Constants::HEADER_UCBROWSER_UA => $userAgent,
        ];

        $original = new GenericRequest($headers);
        $ua       = $original->getBrowserUserAgent();

        self::assertEquals($userAgent, $ua);
    }
}
