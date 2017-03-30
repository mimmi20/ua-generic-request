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
class GenericRequestTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruct()
    {
        $userAgent = 'testUA';
        $browserUa = 'testBrowserUA';
        $deviceUa  = 'testDeviceUA';
        $profile   = 'testProfile';
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
            Constants::HEADER_DEVICE_UA      => $deviceUa,
            Constants::HEADER_UCBROWSER_UA   => $browserUa,
            Constants::HEADER_PROFILE        => $profile,
        ];

        $object = new GenericRequest($headers, false);

        self::assertSame($userAgent, $object->getUserAgent());
        self::assertSame($headers, $object->getHeaders());
        self::assertFalse($object->isXhtmlDevice());
        self::assertSame($profile, $object->getUserAgentProfile());
        self::assertSame($browserUa, $object->getBrowserUserAgent());
        self::assertSame($deviceUa, $object->getDeviceUserAgent());
        self::assertSame(hash('sha512', $userAgent), $object->getId());

        self::assertSame($userAgent, $object->getOriginalHeader(Constants::HEADER_HTTP_USERAGENT));
        self::assertSame('', $object->getOriginalHeader(Constants::HEADER_DEVICE_STOCK_UA));
    }

    public function testToarray()
    {
        $userAgent = 'testUA';
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $original   = new GenericRequest($headers);
        $array      = $original->toArray();
        $object     = (new GenericRequestFactory())->fromArray($array);

        self::assertEquals($original, $object);
    }

    public function testToarraySimple()
    {
        $userAgent = 'testUA';
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $original   = new GenericRequest($headers);
        $array      = $original->toArray(false);

        self::assertEquals($headers, $array);
    }
}
