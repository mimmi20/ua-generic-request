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

use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use UaParser\ClientCodeInterface;
use UaParser\ClientVersionInterface;
use UaParser\DeviceCodeInterface;
use UaParser\EngineCodeInterface;
use UaParser\PlatformCodeInterface;
use UaRequest\Header\XOperaminiPhoneUa;

use function sprintf;

final class XOperaminiPhoneUaTest extends TestCase
{
    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testData(): void
    {
        $ua = 'Microsoft Windows NT 8.10.14219.0;4.0.30508.0;HUAWEI;HUAWEI W2-U00;4a1b5d7105057f0c0208d83c699276ff92cedbff;2.5.0.12';

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
            ->with($ua)
            ->willReturn('zzz');

        $platformCode = $this->createMock(PlatformCodeInterface::class);
        $platformCode
            ->expects(self::once())
            ->method('hasPlatformCode')
            ->with($ua)
            ->willReturn(true);
        $platformCode
            ->expects(self::once())
            ->method('getPlatformCode')
            ->with($ua)
            ->willReturn('abc');

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

        $header = new XOperaminiPhoneUa(
            value: $ua,
            deviceCode: $deviceCode,
            clientCode: $clientCode,
            clientVersion: $clientVersion,
            platformCode: $platformCode,
            engineCode: $engineCode,
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
            'zzz',
            $header->getClientVersion(),
        );

        self::assertTrue(
            $header->hasPlatformCode(),
        );

        self::assertSame(
            'abc',
            $header->getPlatformCode(),
        );

        self::assertFalse(
            $header->hasPlatformVersion(),
        );

        self::assertNull(
            $header->getPlatformVersion(),
        );

        self::assertTrue(
            $header->hasEngineCode(),
        );

        self::assertSame(
            'ghi',
            $header->getEngineCode(),
        );

        self::assertFalse(
            $header->hasEngineVersion(),
        );

        self::assertNull(
            $header->getEngineVersion(),
        );
    }
}
