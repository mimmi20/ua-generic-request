<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2020, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace UaRequestTest;

use BrowserDetector\Loader\NotFoundException;
use PHPUnit\Framework\TestCase;
use UaRequest\Constants;
use UaRequest\GenericRequest;
use UaRequest\GenericRequestFactory;
use UaRequest\Header\HeaderInterface;
use UaRequest\Header\HeaderLoaderInterface;
use Zend\Diactoros\ServerRequestFactory;

final class GenericRequestTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
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
            'user-agent' => $userAgent,
            'device-stock-ua' => $deviceUa,
            'x-ucbrowser-ua' => $browserUa,
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
        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::exactly(3))
            ->method('load')
            ->withConsecutive(['x-ucbrowser-ua'], ['device-stock-ua'], ['user-agent'])
            ->willReturnOnConsecutiveCalls($header1, $header2, $header3);

        /** @var HeaderLoaderInterface $loader */
        $object = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);

        self::assertSame($expectedHeaders, $object->getHeaders());
        self::assertSame($browserUa, $object->getBrowserUserAgent(), 'browser ua mismatch');
        self::assertSame($deviceUa, $object->getDeviceUserAgent(), 'device ua mismatch');
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testToarray(): void
    {
        $userAgent = 'testUA';
        $headers   = [Constants::HEADER_HTTP_USERAGENT => $userAgent];

        $expectedHeaders = ['user-agent' => $userAgent];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::never())
            ->method('getValue')
            ->willReturn($userAgent);
        $header->expects(self::never())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(false);
        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->willReturn($header);

        /** @var HeaderLoaderInterface $loader */
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $array    = $original->getHeaders();
        $object   = (new GenericRequestFactory())->createRequestFromArray($array);

        self::assertSame($expectedHeaders, $object->getHeaders());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testToarraySimple(): void
    {
        $userAgent = 'testUA';
        $headers   = [Constants::HEADER_HTTP_USERAGENT => $userAgent];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::never())
            ->method('getValue')
            ->willReturn($userAgent);
        $header->expects(self::never())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(false);
        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->willReturn($header);

        /** @var HeaderLoaderInterface $loader */
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $array    = $original->getHeaders();

        self::assertSame(['user-agent' => $userAgent], $array);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testForDevice(): void
    {
        $userAgent = 'testUA';
        $headers   = ['HTTP_DEVICE_STOCK_UA' => $userAgent];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::never())
            ->method('getValue')
            ->willReturn($userAgent);
        $header->expects(self::never())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header->expects(self::once())
            ->method('hasDeviceInfo')
            ->willReturn(false);
        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->willReturn($header);

        /** @var HeaderLoaderInterface $loader */
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getDeviceUserAgent();

        self::assertSame('', $ua);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
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
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header1->expects(self::never())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header1->expects(self::never())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header1->expects(self::once())
            ->method('hasDeviceInfo')
            ->willReturn(true);
        $header2 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header2->expects(self::never())
            ->method('getValue')
            ->willReturn($userAgent2);
        $header2->expects(self::never())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header2->expects(self::never())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header2->expects(self::never())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header2->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(true);
        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::exactly(2))
            ->method('load')
            ->withConsecutive(['device-stock-ua'], ['user-agent'])
            ->willReturnOnConsecutiveCalls($header1, $header2);

        /** @var HeaderLoaderInterface $loader */
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getDeviceUserAgent();

        self::assertSame($userAgent, $ua);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testForBrowser(): void
    {
        $userAgent = 'SAMSUNG-GT-S8500';
        $headers   = ['HTTP_DEVICE_STOCK_UA' => $userAgent];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::never())
            ->method('getValue')
            ->willReturn($userAgent);
        $header->expects(self::never())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header->expects(self::once())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(false);
        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->willReturn($header);

        /** @var HeaderLoaderInterface $loader */
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getBrowserUserAgent();

        self::assertSame('', $ua);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
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
            ->method('getValue')
            ->willReturn($userAgent);
        $header1->expects(self::never())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header1->expects(self::once())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header1->expects(self::never())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header1->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(true);
        $header2 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header2->expects(self::once())
            ->method('getValue')
            ->willReturn($userAgent2);
        $header2->expects(self::never())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header2->expects(self::once())
            ->method('hasBrowserInfo')
            ->willReturn(true);
        $header2->expects(self::never())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header2->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(true);
        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::exactly(2))
            ->method('load')
            ->withConsecutive(['x-ucbrowser-ua'], ['device-stock-ua'])
            ->willReturnOnConsecutiveCalls($header1, $header2);

        /** @var HeaderLoaderInterface $loader */
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getBrowserUserAgent();

        self::assertSame($userAgent2, $ua);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testForPlatform(): void
    {
        $userAgent = 'SAMSUNG-GT-S8500';
        $headers   = ['HTTP_DEVICE_STOCK_UA' => $userAgent];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::never())
            ->method('getValue')
            ->willReturn($userAgent);
        $header->expects(self::once())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(false);
        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->willReturn($header);

        /** @var HeaderLoaderInterface $loader */
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getPlatformUserAgent();

        self::assertSame('', $ua);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
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
            ->method('getValue')
            ->willReturn($userAgent);
        $header1->expects(self::once())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header1->expects(self::never())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header1->expects(self::never())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header1->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(true);
        $header2 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header2->expects(self::once())
            ->method('getValue')
            ->willReturn($userAgent2);
        $header2->expects(self::once())
            ->method('hasPlatformInfo')
            ->willReturn(true);
        $header2->expects(self::never())
            ->method('hasBrowserInfo')
            ->willReturn(true);
        $header2->expects(self::never())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header2->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(true);
        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::exactly(2))
            ->method('load')
            ->withConsecutive(['device-stock-ua'], ['ua-os'])
            ->willReturnOnConsecutiveCalls($header1, $header2);

        /** @var HeaderLoaderInterface $loader */
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getPlatformUserAgent();

        self::assertSame($userAgent2, $ua);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testForEngine(): void
    {
        $userAgent = 'SAMSUNG-GT-S8500';
        $headers   = ['HTTP_DEVICE_STOCK_UA' => $userAgent];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::never())
            ->method('getValue')
            ->willReturn($userAgent);
        $header->expects(self::never())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header->expects(self::once())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(false);
        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->willReturn($header);

        /** @var HeaderLoaderInterface $loader */
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getEngineUserAgent();

        self::assertSame('', $ua);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
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
            ->method('getValue')
            ->willReturn($userAgent);
        $header1->expects(self::never())
            ->method('hasPlatformInfo')
            ->willReturn(false);
        $header1->expects(self::never())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header1->expects(self::once())
            ->method('hasEngineInfo')
            ->willReturn(false);
        $header1->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(true);
        $header2 = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header2->expects(self::once())
            ->method('getValue')
            ->willReturn($userAgent2);
        $header2->expects(self::never())
            ->method('hasPlatformInfo')
            ->willReturn(true);
        $header2->expects(self::never())
            ->method('hasBrowserInfo')
            ->willReturn(true);
        $header2->expects(self::once())
            ->method('hasEngineInfo')
            ->willReturn(true);
        $header2->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(true);
        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::exactly(2))
            ->method('load')
            ->withConsecutive(['device-stock-ua'], ['ua-os'])
            ->willReturnOnConsecutiveCalls($header1, $header2);

        /** @var HeaderLoaderInterface $loader */
        $original = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $ua       = $original->getEngineUserAgent();

        self::assertSame($userAgent2, $ua);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testGetFilteredHeaders(): void
    {
        $userAgent       = 'SAMSUNG-GT-S8500';
        $expectedHeaders = ['device-stock-ua' => $userAgent];
        $headers         = [
            'HTTP_DEVICE_STOCK_UA' => $userAgent,
            'via' => 'test',
        ];

        $header = $this->getMockBuilder(HeaderInterface::class)
            ->getMock();
        $header->expects(self::once())
            ->method('getValue')
            ->willReturn($userAgent);
        $header->expects(self::never())
            ->method('hasPlatformInfo')
            ->willReturn(true);
        $header->expects(self::never())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(false);
        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->willReturn($header);

        /** @var HeaderLoaderInterface $loader */
        $original      = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $resultHeaders = $original->getFilteredHeaders();

        self::assertSame($expectedHeaders, $resultHeaders);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
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
            ->method('hasPlatformInfo')
            ->willReturn(true);
        $header->expects(self::never())
            ->method('hasBrowserInfo')
            ->willReturn(false);
        $header->expects(self::never())
            ->method('hasDeviceInfo')
            ->willReturn(false);
        $loader = $this->getMockBuilder(HeaderLoaderInterface::class)
            ->getMock();
        $loader->expects(self::once())
            ->method('load')
            ->willThrowException(new NotFoundException('not-found'));

        /** @var HeaderLoaderInterface $loader */
        $original      = new GenericRequest(ServerRequestFactory::fromGlobals($headers), $loader);
        $resultHeaders = $original->getFilteredHeaders();

        self::assertSame($expectedHeaders, $resultHeaders);
    }
}
