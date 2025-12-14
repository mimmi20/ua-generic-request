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

namespace Header;

use BrowserDetector\Version\Exception\NotNumericException;
use BrowserDetector\Version\Version;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use UaParser\PlatformCodeInterface;
use UaParser\PlatformVersionInterface;
use UaRequest\Header\PlatformHeader;

use function sprintf;

final class PlatformHeaderTest extends TestCase
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

        $versionPlatform = new Version('4');

        $platformCode = $this->createMock(PlatformCodeInterface::class);
        $platformCode
            ->expects(self::exactly(2))
            ->method('hasPlatformCode')
            ->with($ua)
            ->willReturn(true);
        $platformCode
            ->expects(self::once())
            ->method('getPlatformCode')
            ->with($ua)
            ->willReturn('xxx');

        $platformVersion = $this->createMock(PlatformVersionInterface::class);
        $platformVersion
            ->expects(self::once())
            ->method('hasPlatformVersion')
            ->with($ua)
            ->willReturn(true);
        $platformVersion
            ->expects(self::once())
            ->method('getPlatformVersion')
            ->with($ua)
            ->willReturn($versionPlatform);

        $header = new PlatformHeader($ua, $platformCode, $platformVersion);

        self::assertSame($ua, $header->getValue(), sprintf('value mismatch for ua "%s"', $ua));

        self::assertTrue(
            $header->hasPlatformCode(),
        );

        self::assertSame(
            'xxx',
            $header->getPlatformCode(),
        );

        self::assertTrue(
            $header->hasPlatformVersion(),
        );

        self::assertSame(
            $versionPlatform,
            $header->getPlatformVersion(),
        );
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws NotNumericException
     */
    public function testData2(): void
    {
        $ua = 'Microsoft Windows NT 8.10.14219.0;4.0.30508.0;HUAWEI;HUAWEI W2-U00;4a1b5d7105057f0c0208d83c699276ff92cedbff;2.5.0.12';

        $versionPlatform = new Version('4');

        $platformCode = $this->createMock(PlatformCodeInterface::class);
        $platformCode
            ->expects(self::exactly(2))
            ->method('hasPlatformCode')
            ->with($ua)
            ->willReturn(false);
        $platformCode
            ->expects(self::once())
            ->method('getPlatformCode')
            ->with($ua)
            ->willReturn('xxx');

        $platformVersion = $this->createMock(PlatformVersionInterface::class);
        $platformVersion
            ->expects(self::never())
            ->method('hasPlatformVersion');
        $platformVersion
            ->expects(self::once())
            ->method('getPlatformVersion')
            ->with($ua)
            ->willReturn($versionPlatform);

        $header = new PlatformHeader($ua, $platformCode, $platformVersion);

        self::assertSame($ua, $header->getValue(), sprintf('value mismatch for ua "%s"', $ua));

        self::assertFalse(
            $header->hasPlatformCode(),
        );

        self::assertSame(
            'xxx',
            $header->getPlatformCode(),
        );

        self::assertFalse(
            $header->hasPlatformVersion(),
        );

        self::assertSame(
            $versionPlatform,
            $header->getPlatformVersion(),
        );
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws NotNumericException
     */
    public function testData3(): void
    {
        $ua = 'Microsoft Windows NT 8.10.14219.0;4.0.30508.0;HUAWEI;HUAWEI W2-U00;4a1b5d7105057f0c0208d83c699276ff92cedbff;2.5.0.12';

        $versionPlatform = new Version('4');

        $platformCode = $this->createMock(PlatformCodeInterface::class);
        $platformCode
            ->expects(self::exactly(2))
            ->method('hasPlatformCode')
            ->with($ua)
            ->willReturn(true);
        $platformCode
            ->expects(self::once())
            ->method('getPlatformCode')
            ->with($ua)
            ->willReturn('xxx');

        $platformVersion = $this->createMock(PlatformVersionInterface::class);
        $platformVersion
            ->expects(self::once())
            ->method('hasPlatformVersion')
            ->with($ua)
            ->willReturn(false);
        $platformVersion
            ->expects(self::once())
            ->method('getPlatformVersion')
            ->with($ua)
            ->willReturn($versionPlatform);

        $header = new PlatformHeader($ua, $platformCode, $platformVersion);

        self::assertSame($ua, $header->getValue(), sprintf('value mismatch for ua "%s"', $ua));

        self::assertTrue(
            $header->hasPlatformCode(),
        );

        self::assertSame(
            'xxx',
            $header->getPlatformCode(),
        );

        self::assertFalse(
            $header->hasPlatformVersion(),
        );

        self::assertSame(
            $versionPlatform,
            $header->getPlatformVersion(),
        );
    }
}
