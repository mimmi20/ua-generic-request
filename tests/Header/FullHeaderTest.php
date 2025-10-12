<?php

/**
 * This file is part of the mimmi20/ua-generic-request package.
 *
 * Copyright (c) 2015-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequestTest\Header;

use BrowserDetector\Version\Exception\NotNumericException;
use BrowserDetector\Version\Version;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use UaParser\ClientCodeInterface;
use UaParser\ClientVersionInterface;
use UaParser\DeviceCodeInterface;
use UaParser\EngineCodeInterface;
use UaParser\EngineVersionInterface;
use UaParser\PlatformCodeInterface;
use UaParser\PlatformVersionInterface;
use UaRequest\Header\FullHeader;

use function sprintf;

final class FullHeaderTest extends TestCase
{
    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws NotNumericException
     */
    public function testData(): void
    {
        $ua = 'Microsoft Windows NT 8.10.14219.0;4.0.30508.0;HUAWEI;HUAWEI W2-U00;4a1b5d7105057f0c0208d83c699276ff92cedbff;2.5.0.12';

        $versionClient = new Version('4');
        $versionOs     = new Version('6');
        $versionEngine = new Version('10');

        $deviceCode = $this->createMock(DeviceCodeInterface::class);
        $deviceCode
            ->expects(self::once())
            ->method('hasDeviceCode')
            ->with($ua)
            ->willReturn(true);
        $deviceCode
            ->expects(self::once())
            ->method('getDeviceCode')
            ->with($ua)
            ->willReturn('xxx');

        $clientCode = $this->createMock(ClientCodeInterface::class);
        $clientCode
            ->expects(self::once())
            ->method('hasClientCode')
            ->with($ua)
            ->willReturn(true);
        $clientCode
            ->expects(self::once())
            ->method('getClientCode')
            ->with($ua)
            ->willReturn('yyy');

        $clientVersion = $this->createMock(ClientVersionInterface::class);
        $clientVersion
            ->expects(self::once())
            ->method('hasClientVersion')
            ->with($ua)
            ->willReturn(true);
        $clientVersion
            ->expects(self::once())
            ->method('getClientVersion')
            ->with($ua, null)
            ->willReturn($versionClient);

        $platformCode = $this->createMock(PlatformCodeInterface::class);
        $platformCode
            ->expects(self::once())
            ->method('hasPlatformCode')
            ->with($ua)
            ->willReturn(true);
        $platformCode
            ->expects(self::once())
            ->method('getPlatformCode')
            ->with($ua, null)
            ->willReturn('abc');

        $platformVersion = $this->createMock(PlatformVersionInterface::class);
        $platformVersion
            ->expects(self::once())
            ->method('hasPlatformVersion')
            ->with($ua)
            ->willReturn(true);
        $platformVersion
            ->expects(self::once())
            ->method('getPlatformVersion')
            ->with($ua, null)
            ->willReturn($versionOs);

        $engineCode = $this->createMock(EngineCodeInterface::class);
        $engineCode
            ->expects(self::once())
            ->method('hasEngineCode')
            ->with($ua)
            ->willReturn(true);
        $engineCode
            ->expects(self::once())
            ->method('getEngineCode')
            ->with($ua)
            ->willReturn('ghi');

        $engineVersion = $this->createMock(EngineVersionInterface::class);
        $engineVersion
            ->expects(self::once())
            ->method('hasEngineVersion')
            ->with($ua)
            ->willReturn(true);
        $engineVersion
            ->expects(self::once())
            ->method('getEngineVersion')
            ->with($ua, null)
            ->willReturn($versionEngine);

        $header = new FullHeader(
            value: $ua,
            deviceCode: $deviceCode,
            clientCode: $clientCode,
            clientVersion: $clientVersion,
            platformCode: $platformCode,
            platformVersion: $platformVersion,
            engineCode: $engineCode,
            engineVersion: $engineVersion,
        );

        self::assertSame($ua, $header->getValue(), sprintf('value mismatch for ua "%s"', $ua));

        self::assertTrue(
            $header->hasDeviceCode(),
        );

        self::assertSame(
            'xxx',
            $header->getDeviceCode(),
        );

        self::assertTrue(
            $header->hasClientCode(),
        );

        self::assertSame(
            'yyy',
            $header->getClientCode(),
        );

        self::assertTrue(
            $header->hasClientVersion(),
        );

        self::assertSame(
            $versionClient,
            $header->getClientVersion(),
        );

        self::assertTrue(
            $header->hasPlatformCode(),
        );

        self::assertSame(
            'abc',
            $header->getPlatformCode(),
        );

        self::assertTrue(
            $header->hasPlatformVersion(),
        );

        self::assertSame(
            $versionOs,
            $header->getPlatformVersion(),
        );

        self::assertTrue(
            $header->hasEngineCode(),
        );

        self::assertSame(
            'ghi',
            $header->getEngineCode(),
        );

        self::assertTrue(
            $header->hasEngineVersion(),
        );

        self::assertSame(
            $versionEngine,
            $header->getEngineVersion(),
        );
    }
}
