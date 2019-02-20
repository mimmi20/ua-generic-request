<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2019, Thomas Mueller <mimmi20@live.de>
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
use UaRequest\Header\HeaderLoaderInterface;
use Zend\Diactoros\ServerRequestFactory;

final class GenericRequestTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstruct(): void
    {
        $userAgent = 'testUA';
        $browserUa = 'pr(testBrowserUA)';
        $deviceUa  = 'testDeviceUA';
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
            'HTTP_DEVICE_STOCK_UA'           => $deviceUa,
            'HTTP_X_UCBROWSER_UA'            => $browserUa,
        ];

        $expectedHeaders = [
            'user-agent'      => $userAgent,
            'device-stock-ua' => $deviceUa,
            'x-ucbrowser-ua'  => $browserUa,
        ];

        /** @var HeaderLoaderInterface $loader */
        $loader = $this->createMock(HeaderLoaderInterface::class);

        $object = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);

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

        $expectedHeaders = [
            'user-agent' => $userAgent,
        ];

        /** @var HeaderLoaderInterface $loader */
        $loader = $this->createMock(HeaderLoaderInterface::class);

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $array    = $original->getHeaders();
        $object   = (new GenericRequestFactory())->createRequestFromArray($array);

        self::assertSame($expectedHeaders, $object->getHeaders());
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

        /** @var HeaderLoaderInterface $loader */
        $loader = $this->createMock(HeaderLoaderInterface::class);

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
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
            'HTTP_DEVICE_STOCK_UA' => $userAgent,
        ];

        /** @var HeaderLoaderInterface $loader */
        $loader = $this->createMock(HeaderLoaderInterface::class);

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
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
            'HTTP_DEVICE_STOCK_UA'           => $userAgent,
            Constants::HEADER_HTTP_USERAGENT => $userAgent2,
        ];

        /** @var HeaderLoaderInterface $loader */
        $loader = $this->createMock(HeaderLoaderInterface::class);

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
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
            'HTTP_DEVICE_STOCK_UA' => $userAgent,
        ];

        /** @var HeaderLoaderInterface $loader */
        $loader = $this->createMock(HeaderLoaderInterface::class);

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
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
            'HTTP_DEVICE_STOCK_UA' => $userAgent,
            'HTTP_X_UCBROWSER_UA'  => $userAgent2,
        ];

        /** @var HeaderLoaderInterface $loader */
        $loader = $this->createMock(HeaderLoaderInterface::class);

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getBrowserUserAgent();

        self::assertEquals($userAgent2, $ua);
    }

    /**
     * @return void
     */
    public function testForPlatform(): void
    {
        $userAgent = 'testUA';
        $headers   = [
            'HTTP_DEVICE_STOCK_UA' => $userAgent,
        ];

        /** @var HeaderLoaderInterface $loader */
        $loader = $this->createMock(HeaderLoaderInterface::class);

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getPlatformUserAgent();

        self::assertEquals('', $ua);
    }

    /**
     * @return void
     */
    public function testForPlatform2(): void
    {
        $userAgent  = 'testUA';
        $userAgent2 = 'testUA2';
        $headers    = [
            'HTTP_DEVICE_STOCK_UA' => $userAgent,
            'HTTP_UA_OS'           => $userAgent2,
        ];

        /** @var HeaderLoaderInterface $loader */
        $loader = $this->createMock(HeaderLoaderInterface::class);

        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getPlatformUserAgent();

        self::assertEquals($userAgent2, $ua);
    }

    /**
     * @return void
     */
    public function testGetFilteredHeaders(): void
    {
        $userAgent       = 'testUA';
        $expectedHeaders = [
            'device-stock-ua' => $userAgent,
        ];
        $headers = [
            'HTTP_DEVICE_STOCK_UA' => $userAgent,
            'via'                  => 'test',
        ];

        /** @var HeaderLoaderInterface $loader */
        $loader = $this->createMock(HeaderLoaderInterface::class);

        $original      = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $resultHeaders = $original->getFilteredHeaders();

        self::assertEquals($expectedHeaders, $resultHeaders);
    }
}
