<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2023, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequestTest;

use Laminas\Diactoros\ServerRequestFactory;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use UaRequest\Constants;
use UaRequest\GenericRequest;
use UaRequest\GenericRequestFactory;
use UaRequest\Header\HeaderInterface;
use UaRequest\Header\HeaderLoaderInterface;
use UaRequest\NotFoundException;

use function assert;

final class GenericRequestTest extends TestCase
{
    /** @throws Exception */
    public function testConstruct(): void
    {
        $userAgent = 'testUA';
        $browserUa = 'pr(testBrowserUA)';
        $deviceUa  = 'testDeviceUA';
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
            'HTTP_DEVICE_STOCK_UA' => $deviceUa,
            'HTTP_X_UCBROWSER_UA' => $browserUa,
        ];

        $expectedHeaders = [
            Constants::HEADER_USERAGENT => $userAgent,
            Constants::HEADER_DEVICE_STOCK_UA => $deviceUa,
            Constants::HEADER_UCBROWSER_UA => $browserUa,
        ];

        $header1 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header1->expects(self::any())
            ->method('getValue')
            ->willReturn($deviceUa);
        $header1->expects(self::any())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header1->expects(self::any())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header1->expects(self::any())
            ->method('hasDeviceInfo')
            ->willReturn(true);
        $header2 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header2->expects(self::any())
            ->method('getValue')
            ->willReturn($browserUa);
        $header2->expects(self::any())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header2->expects(self::any())
            ->method('hasBrowserInfo')
            ->willReturn(true);
        $header2->expects(self::any())
            ->method('hasEngineInfo')
            ->willReturn(true);
        $header2->expects(self::any())
            ->method('hasDeviceInfo')
            ->willReturn(false);
        $header3 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header3->expects(self::any())
            ->method('getValue')
            ->willReturn($userAgent);
        $header3->expects(self::any())
            ->method('hasPlatformInfo')
            ->willReturn(true);
        $header3->expects(self::any())
            ->method('hasBrowserInfo')
            ->willReturn(true);
        $header3->expects(self::any())
            ->method('hasEngineInfo')
            ->willReturn(true);
        $header3->expects(self::any())
            ->method('hasDeviceInfo')
            ->willReturn(true);

        $loader  = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $matcher = self::exactly(3);
        $loader->expects($matcher)
            ->method('load')
            ->willReturnCallback(
                static function (string $key, string $value) use ($matcher, $browserUa, $deviceUa, $userAgent, $header1, $header2, $header3): HeaderInterface {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(Constants::HEADER_UCBROWSER_UA, $key),
                        2 => self::assertSame(Constants::HEADER_DEVICE_STOCK_UA, $key),
                        default => self::assertSame(Constants::HEADER_USERAGENT, $key),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame($browserUa, $value),
                        2 => self::assertSame($deviceUa, $value),
                        default => self::assertSame($userAgent, $value),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $header1,
                        2 => $header2,
                        default => $header3,
                    };
                },
            );

        assert($loader instanceof HeaderLoaderInterface);
        $object = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);

        self::assertSame($expectedHeaders, $object->getHeaders());
        self::assertSame($browserUa, $object->getBrowserUserAgent(), 'browser ua mismatch');
        self::assertSame($deviceUa, $object->getDeviceUserAgent(), 'device ua mismatch');
    }

    /** @throws Exception */
    public function testToarray(): void
    {
        $userAgent = 'testUA';
        $headers   = [Constants::HEADER_HTTP_USERAGENT => $userAgent];

        $expectedHeaders = [Constants::HEADER_USERAGENT => $userAgent];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::never())
            ->method('getValue');
        $header->expects(self::never())
            ->method('hasPlatformInfo');
        $header->expects(self::never())
            ->method('hasBrowserInfo');
        $header->expects(self::never())
            ->method('hasEngineInfo');
        $header->expects(self::never())
            ->method('hasDeviceInfo');

        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->with(Constants::HEADER_USERAGENT, $userAgent)
            ->willReturn($header);

        assert($loader instanceof HeaderLoaderInterface);
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $array    = $original->getHeaders();
        $object   = (new GenericRequestFactory())->createRequestFromArray($array);

        self::assertSame($expectedHeaders, $object->getHeaders());
    }

    /** @throws Exception */
    public function testToarraySimple(): void
    {
        $userAgent = 'testUA';
        $headers   = [Constants::HEADER_HTTP_USERAGENT => $userAgent];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::never())
            ->method('getValue');
        $header->expects(self::never())
            ->method('hasPlatformInfo');
        $header->expects(self::never())
            ->method('hasBrowserInfo');
        $header->expects(self::never())
            ->method('hasEngineInfo');
        $header->expects(self::never())
            ->method('hasDeviceInfo');

        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->with(Constants::HEADER_USERAGENT, $userAgent)
            ->willReturn($header);

        assert($loader instanceof HeaderLoaderInterface);
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $array    = $original->getHeaders();

        self::assertSame([Constants::HEADER_USERAGENT => $userAgent], $array);
    }

    /** @throws Exception */
    public function testForDevice(): void
    {
        $userAgent = 'testUA';
        $headers   = ['HTTP_DEVICE_STOCK_UA' => $userAgent];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::never())
            ->method('getValue');
        $header->expects(self::never())
            ->method('hasPlatformInfo');
        $header->expects(self::never())
            ->method('hasBrowserInfo');
        $header->expects(self::never())
            ->method('hasEngineInfo');
        $header->expects(self::once())
            ->method('hasDeviceInfo');

        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->with(Constants::HEADER_DEVICE_STOCK_UA, $userAgent)
            ->willReturn($header);

        assert($loader instanceof HeaderLoaderInterface);
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getDeviceUserAgent();

        self::assertSame('', $ua);
    }

    /** @throws Exception */
    public function testForDevice2(): void
    {
        $userAgent  = 'SAMSUNG-GT-S8500';
        $userAgent2 = 'testUA2';
        $headers    = [
            'HTTP_DEVICE_STOCK_UA' => $userAgent,
            'HTTP_USER_AGENT' => $userAgent2,
        ];

        $header1 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header1->expects(self::once())
            ->method('getValue')
            ->willReturn($userAgent);
        $header1->expects(self::never())
            ->method('hasPlatformInfo');
        $header1->expects(self::never())
            ->method('hasBrowserInfo');
        $header1->expects(self::never())
            ->method('hasEngineInfo');
        $header1->expects(self::once())
            ->method('hasDeviceInfo')
            ->willReturn(true);

        $header2 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header2->expects(self::never())
            ->method('getValue');
        $header2->expects(self::never())
            ->method('hasPlatformInfo');
        $header2->expects(self::never())
            ->method('hasBrowserInfo');
        $header2->expects(self::never())
            ->method('hasEngineInfo');
        $header2->expects(self::never())
            ->method('hasDeviceInfo');

        $loader  = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $matcher = self::exactly(2);
        $loader->expects($matcher)
            ->method('load')
            ->willReturnCallback(
                static function (string $key, string $value) use ($matcher, $userAgent, $userAgent2, $header1, $header2): HeaderInterface {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(Constants::HEADER_DEVICE_STOCK_UA, $key),
                        default => self::assertSame(Constants::HEADER_USERAGENT, $key),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame($userAgent, $value),
                        default => self::assertSame($userAgent2, $value),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $header1,
                        default => $header2,
                    };
                },
            );

        assert($loader instanceof HeaderLoaderInterface);
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getDeviceUserAgent();

        self::assertSame($userAgent, $ua);
    }

    /** @throws Exception */
    public function testForBrowser(): void
    {
        $userAgent = 'SAMSUNG-GT-S8500';
        $headers   = ['HTTP_DEVICE_STOCK_UA' => $userAgent];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::never())
            ->method('getValue');
        $header->expects(self::never())
            ->method('hasPlatformInfo');
        $header->expects(self::once())
            ->method('hasBrowserInfo');
        $header->expects(self::never())
            ->method('hasEngineInfo');
        $header->expects(self::never())
            ->method('hasDeviceInfo');

        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->with(Constants::HEADER_DEVICE_STOCK_UA, $userAgent)
            ->willReturn($header);

        assert($loader instanceof HeaderLoaderInterface);
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getBrowserUserAgent();

        self::assertSame('', $ua);
    }

    /** @throws Exception */
    public function testForBrowser2(): void
    {
        $userAgent  = 'SAMSUNG-GT-S8500';
        $userAgent2 = 'testUA2';
        $headers    = [
            'HTTP_DEVICE_STOCK_UA' => $userAgent,
            'HTTP_X_UCBROWSER_UA' => $userAgent2,
        ];

        $header1 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header1->expects(self::never())
            ->method('getValue');
        $header1->expects(self::never())
            ->method('hasPlatformInfo');
        $header1->expects(self::once())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header1->expects(self::never())
            ->method('hasEngineInfo');
        $header1->expects(self::never())
            ->method('hasDeviceInfo');
        $header2 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header2->expects(self::once())
            ->method('getValue')
            ->willReturn($userAgent2);
        $header2->expects(self::never())
            ->method('hasPlatformInfo');
        $header2->expects(self::once())
            ->method('hasBrowserInfo')
            ->willReturn(true);
        $header2->expects(self::never())
            ->method('hasEngineInfo');
        $header2->expects(self::never())
            ->method('hasDeviceInfo');

        $loader  = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $matcher = self::exactly(2);
        $loader->expects($matcher)
            ->method('load')
            ->willReturnCallback(
                static function (string $key, string $value) use ($matcher, $userAgent, $userAgent2, $header1, $header2): HeaderInterface {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(Constants::HEADER_UCBROWSER_UA, $key),
                        default => self::assertSame(Constants::HEADER_DEVICE_STOCK_UA, $key),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame($userAgent2, $value),
                        default => self::assertSame($userAgent, $value),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $header1,
                        default => $header2,
                    };
                },
            );

        assert($loader instanceof HeaderLoaderInterface);
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getBrowserUserAgent();

        self::assertSame($userAgent2, $ua);
    }

    /** @throws Exception */
    public function testForPlatform(): void
    {
        $userAgent = 'SAMSUNG-GT-S8500';
        $headers   = ['HTTP_DEVICE_STOCK_UA' => $userAgent];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::never())
            ->method('getValue');
        $header->expects(self::once())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasBrowserInfo');
        $header->expects(self::never())
            ->method('hasEngineInfo');
        $header->expects(self::never())
            ->method('hasDeviceInfo');

        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->with(Constants::HEADER_DEVICE_STOCK_UA, $userAgent)
            ->willReturn($header);

        assert($loader instanceof HeaderLoaderInterface);
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getPlatformUserAgent();

        self::assertSame('', $ua);
    }

    /** @throws Exception */
    public function testForPlatform2(): void
    {
        $userAgent  = 'SAMSUNG-GT-S8500';
        $userAgent2 = 'Windows CE (Smartphone) - Version 5.2';
        $headers    = [
            'HTTP_DEVICE_STOCK_UA' => $userAgent,
            'HTTP_UA_OS' => $userAgent2,
        ];

        $header1 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header1->expects(self::never())
            ->method('getValue');
        $header1->expects(self::once())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header1->expects(self::never())
            ->method('hasBrowserInfo');
        $header1->expects(self::never())
            ->method('hasEngineInfo');
        $header1->expects(self::never())
            ->method('hasDeviceInfo');
        $header2 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header2->expects(self::once())
            ->method('getValue')
            ->willReturn($userAgent2);
        $header2->expects(self::once())
            ->method('hasPlatformInfo')
            ->willReturn(true);
        $header2->expects(self::never())
            ->method('hasBrowserInfo');
        $header2->expects(self::never())
            ->method('hasEngineInfo');
        $header2->expects(self::never())
            ->method('hasDeviceInfo');

        $loader  = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $matcher = self::exactly(2);
        $loader->expects($matcher)
            ->method('load')
            ->willReturnCallback(
                static function (string $key, string $value) use ($matcher, $userAgent2, $userAgent, $header1, $header2): HeaderInterface {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(Constants::HEADER_DEVICE_STOCK_UA, $key),
                        default => self::assertSame(Constants::HEADER_UA_OS, $key),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame($userAgent, $value),
                        default => self::assertSame($userAgent2, $value),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $header1,
                        default => $header2,
                    };
                },
            );

        assert($loader instanceof HeaderLoaderInterface);
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getPlatformUserAgent();

        self::assertSame($userAgent2, $ua);
    }

    /** @throws Exception */
    public function testForEngine(): void
    {
        $userAgent = 'SAMSUNG-GT-S8500';
        $headers   = ['HTTP_DEVICE_STOCK_UA' => $userAgent];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::never())
            ->method('getValue');
        $header->expects(self::never())
            ->method('hasPlatformInfo');
        $header->expects(self::never())
            ->method('hasBrowserInfo');
        $header->expects(self::once())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasDeviceInfo');

        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->with(Constants::HEADER_DEVICE_STOCK_UA, $userAgent)
            ->willReturn($header);

        assert($loader instanceof HeaderLoaderInterface);
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getEngineUserAgent();

        self::assertSame('', $ua);
    }

    /** @throws Exception */
    public function testForEngine2(): void
    {
        $userAgent  = 'SAMSUNG-GT-S8500';
        $userAgent2 = 'Windows CE (Smartphone) - Version 5.2';
        $headers    = [
            'HTTP_DEVICE_STOCK_UA' => $userAgent,
            'HTTP_UA_OS' => $userAgent2,
        ];

        $header1 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header1->expects(self::never())
            ->method('getValue');
        $header1->expects(self::never())
            ->method('hasPlatformInfo');
        $header1->expects(self::never())
            ->method('hasBrowserInfo');
        $header1->expects(self::once())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header1->expects(self::never())
            ->method('hasDeviceInfo');
        $header2 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header2->expects(self::once())
            ->method('getValue')
            ->willReturn($userAgent2);
        $header2->expects(self::never())
            ->method('hasPlatformInfo');
        $header2->expects(self::never())
            ->method('hasBrowserInfo');
        $header2->expects(self::once())
            ->method('hasEngineInfo')
            ->willReturn(true);
        $header2->expects(self::never())
            ->method('hasDeviceInfo');

        $loader  = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $matcher = self::exactly(2);
        $loader->expects($matcher)
            ->method('load')
            ->willReturnCallback(
                static function (string $key, string $value) use ($matcher, $userAgent2, $userAgent, $header1, $header2): HeaderInterface {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(Constants::HEADER_DEVICE_STOCK_UA, $key),
                        default => self::assertSame(Constants::HEADER_UA_OS, $key),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame($userAgent, $value),
                        default => self::assertSame($userAgent2, $value),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $header1,
                        default => $header2,
                    };
                },
            );

        assert($loader instanceof HeaderLoaderInterface);
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getEngineUserAgent();

        self::assertSame($userAgent2, $ua);
    }

    /** @throws Exception */
    public function testGetFilteredHeaders(): void
    {
        $userAgent       = 'SAMSUNG-GT-S8500';
        $browserUa       = 'pr(testBrowserUA)';
        $deviceUa        = 'testDeviceUA';
        $expectedHeaders = [
            Constants::HEADER_UCBROWSER_UA => $browserUa,
            Constants::HEADER_DEVICE_STOCK_UA => $deviceUa,
            Constants::HEADER_USERAGENT => $userAgent,
        ];
        $headers         = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
            'HTTP_DEVICE_STOCK_UA' => $deviceUa,
            'HTTP_X_UCBROWSER_UA' => $browserUa,
            'via' => 'test',
        ];

        $header1 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header1->expects(self::once())
            ->method('getValue')
            ->willReturn($browserUa);
        $header1->expects(self::never())
            ->method('hasPlatformInfo');
        $header1->expects(self::never())
            ->method('hasBrowserInfo');
        $header1->expects(self::never())
            ->method('hasDeviceInfo');

        $header2 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header2->expects(self::once())
            ->method('getValue')
            ->willReturn($deviceUa);
        $header2->expects(self::never())
            ->method('hasPlatformInfo');
        $header2->expects(self::never())
            ->method('hasBrowserInfo');
        $header2->expects(self::never())
            ->method('hasDeviceInfo');

        $header3 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header3->expects(self::once())
            ->method('getValue')
            ->willReturn($userAgent);
        $header3->expects(self::never())
            ->method('hasPlatformInfo');
        $header3->expects(self::never())
            ->method('hasBrowserInfo');
        $header3->expects(self::never())
            ->method('hasDeviceInfo');

        $loader  = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $matcher = self::exactly(3);
        $loader->expects($matcher)
            ->method('load')
            ->willReturnCallback(
                static function (string $key, string $value) use ($matcher, $browserUa, $deviceUa, $userAgent, $header1, $header2, $header3): HeaderInterface {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(Constants::HEADER_UCBROWSER_UA, $key),
                        2 => self::assertSame(Constants::HEADER_DEVICE_STOCK_UA, $key),
                        default => self::assertSame(Constants::HEADER_USERAGENT, $key),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame($browserUa, $value),
                        2 => self::assertSame($deviceUa, $value),
                        default => self::assertSame($userAgent, $value),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $header1,
                        2 => $header2,
                        default => $header3,
                    };
                },
            );

        assert($loader instanceof HeaderLoaderInterface);
        $original      = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $resultHeaders = $original->getFilteredHeaders();

        self::assertSame($expectedHeaders, $resultHeaders);
    }

    /** @throws Exception */
    public function testGetFilteredHeadersWithLoadException(): void
    {
        $userAgent       = 'SAMSUNG-GT-S8500';
        $expectedHeaders = [];
        $headers         = [
            'HTTP_DEVICE_STOCK_UA' => $userAgent,
            'via' => 'test',
        ];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::never())
            ->method('getValue');
        $header->expects(self::never())
            ->method('hasPlatformInfo');
        $header->expects(self::never())
            ->method('hasBrowserInfo');
        $header->expects(self::never())
            ->method('hasDeviceInfo');

        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->with(Constants::HEADER_DEVICE_STOCK_UA, $userAgent)
            ->willThrowException(new NotFoundException('not-found'));

        assert($loader instanceof HeaderLoaderInterface);
        $original      = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $resultHeaders = $original->getFilteredHeaders();

        self::assertSame($expectedHeaders, $resultHeaders);
    }

    /** @throws Exception */
    public function testGetFilteredHeadersWithLoadException2(): void
    {
        $userAgent       = 'SAMSUNG-GT-S8500';
        $browserUa       = 'pr(testBrowserUA)';
        $deviceUa        = 'testDeviceUA';
        $expectedHeaders = [
            Constants::HEADER_UCBROWSER_UA => $browserUa,
            Constants::HEADER_USERAGENT => $userAgent,
        ];
        $headers         = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
            'x-unknown-header' => 'test',
            'HTTP_DEVICE_STOCK_UA' => $deviceUa,
            'HTTP_X_UCBROWSER_UA' => $browserUa,
            'via' => 'test',
        ];

        $header1 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header1->expects(self::once())
            ->method('getValue')
            ->willReturn($browserUa);
        $header1->expects(self::never())
            ->method('hasPlatformInfo');
        $header1->expects(self::never())
            ->method('hasBrowserInfo');
        $header1->expects(self::never())
            ->method('hasDeviceInfo');

        $header2 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header2->expects(self::once())
            ->method('getValue')
            ->willReturn($userAgent);
        $header2->expects(self::never())
            ->method('hasPlatformInfo');
        $header2->expects(self::never())
            ->method('hasBrowserInfo');
        $header2->expects(self::never())
            ->method('hasDeviceInfo');

        $loader  = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $matcher = self::exactly(3);
        $loader->expects($matcher)
            ->method('load')
            ->willReturnCallback(
                static function (string $key, string $value) use ($matcher, $browserUa, $deviceUa, $userAgent, $header1, $header2): HeaderInterface {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(Constants::HEADER_UCBROWSER_UA, $key),
                        2 => self::assertSame(Constants::HEADER_DEVICE_STOCK_UA, $key),
                        default => self::assertSame(Constants::HEADER_USERAGENT, $key),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame($browserUa, $value),
                        2 => self::assertSame($deviceUa, $value),
                        default => self::assertSame($userAgent, $value),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $header1,
                        2 => throw new NotFoundException('not-found'),
                        default => $header2,
                    };
                },
            );

        assert($loader instanceof HeaderLoaderInterface);
        $original      = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $resultHeaders = $original->getFilteredHeaders();

        self::assertSame($expectedHeaders, $resultHeaders);
    }

    /** @throws Exception */
    public function testGetFilteredHeadersWithLoadException3(): void
    {
        $userAgent       = 'SAMSUNG-GT-S8500';
        $browserUa       = 'pr(testBrowserUA)';
        $deviceUa        = 'testDeviceUA';
        $expectedHeaders = [
            Constants::HEADER_UCBROWSER_UA => $browserUa,
            Constants::HEADER_USERAGENT => $userAgent,
        ];
        $headers         = [
            Constants::HEADER_USERAGENT => $userAgent,
            0 => 'test',
            'x-unknown-header' => 'test',
            Constants::HEADER_DEVICE_STOCK_UA => $deviceUa,
            Constants::HEADER_UCBROWSER_UA => $browserUa,
            'via' => 'test',
        ];

        $header1 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header1->expects(self::once())
            ->method('getValue')
            ->willReturn($browserUa);
        $header1->expects(self::never())
            ->method('hasPlatformInfo');
        $header1->expects(self::never())
            ->method('hasBrowserInfo');
        $header1->expects(self::never())
            ->method('hasDeviceInfo');

        $header2 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header2->expects(self::once())
            ->method('getValue')
            ->willReturn($userAgent);
        $header2->expects(self::never())
            ->method('hasPlatformInfo');
        $header2->expects(self::never())
            ->method('hasBrowserInfo');
        $header2->expects(self::never())
            ->method('hasDeviceInfo');

        $loader  = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher = self::exactly(3);
        $loader->expects($matcher)
            ->method('load')
            ->willReturnCallback(
                static function (string $key, string $value) use ($matcher, $browserUa, $deviceUa, $userAgent, $header1, $header2): HeaderInterface {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(Constants::HEADER_UCBROWSER_UA, $key),
                        2 => self::assertSame(Constants::HEADER_DEVICE_STOCK_UA, $key),
                        default => self::assertSame(Constants::HEADER_USERAGENT, $key),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame($browserUa, $value),
                        2 => self::assertSame($deviceUa, $value),
                        default => self::assertSame($userAgent, $value),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $header1,
                        2 => throw new NotFoundException('not-found'),
                        default => $header2,
                    };
                },
            );

        $message = $this->getMockBuilder(MessageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $message->expects(self::once())
            ->method('getHeaders')
            ->willReturn($headers);
        $matcher = self::exactly(5);
        $message->expects($matcher)
            ->method('getHeaderLine')
            ->willReturnCallback(
                static function (string $name) use ($matcher, $browserUa, $deviceUa, $userAgent): string {
                    match ($matcher->numberOfInvocations()) {
                        2 => self::assertSame('x-unknown-header', $name),
                        3 => self::assertSame(Constants::HEADER_DEVICE_STOCK_UA, $name),
                        4 => self::assertSame(Constants::HEADER_UCBROWSER_UA, $name),
                        5 => self::assertSame('via', $name),
                        default => self::assertSame(Constants::HEADER_USERAGENT, $name),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        2 => 'test',
                        3 => $deviceUa,
                        4 => $browserUa,
                        5 => 'test',
                        default => $userAgent,
                    };
                },
            );

        assert($loader instanceof HeaderLoaderInterface);
        $original      = new GenericRequest($message, $loader);
        $resultHeaders = $original->getFilteredHeaders();

        self::assertSame($expectedHeaders, $resultHeaders);
    }
}
