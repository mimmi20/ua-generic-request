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

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use UaParser\ClientCodeInterface;
use UaParser\DeviceCodeInterface;
use UaRequest\Header\XUcbrowserPhoneUa;

use function sprintf;

final class XUcbrowserPhoneUaTest extends TestCase
{
    /** @throws ExpectationFailedException */
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

        $header = new XUcbrowserPhoneUa(value: $ua, deviceCode: $deviceCode, clientCode: $clientCode);

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

        self::assertFalse(
            $header->hasClientVersion(),
        );

        self::assertNull(
            $header->getClientVersion(),
        );

        self::assertFalse(
            $header->hasPlatformCode(),
        );

        self::assertNull(
            $header->getPlatformCode(),
        );

        self::assertFalse(
            $header->hasPlatformVersion(),
        );

        self::assertNull(
            $header->getPlatformVersion(),
        );

        self::assertFalse(
            $header->hasEngineCode(),
        );

        self::assertNull(
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
