<?php

/**
 * This file is part of the mimmi20/ua-generic-request package.
 *
 * Copyright (c) 2015-2026, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequestTest\Header;

use BrowserDetector\Version\NullVersion;
use Override;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use UaData\CompanyInterface;
use UaData\OsInterface;
use UaParser\DeviceCodeInterface;
use UaParser\PlatformCodeInterface;
use UaRequest\Exception\NotFoundException;
use UaRequest\Header\XUcbrowserDeviceUa;

use function sprintf;

final class XUcbrowserDeviceUaTest extends TestCase
{
    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws NotFoundException
     */
    public function testData(): void
    {
        $ua = 'Microsoft Windows NT 8.10.14219.0;4.0.30508.0;HUAWEI;HUAWEI W2-U00;4a1b5d7105057f0c0208d83c699276ff92cedbff;2.5.0.12';

        $os = new class () implements OsInterface {
            /** @throws void */
            #[Override]
            public function getName(): string | null
            {
                return null;
            }

            /** @throws void */
            #[Override]
            public function getMarketingName(): string | null
            {
                return null;
            }

            /** @throws void */
            #[Override]
            public function getManufacturer(): CompanyInterface
            {
                return new class () implements CompanyInterface {
                    /** @throws void */
                    #[Override]
                    public function getName(): string | null
                    {
                        return null;
                    }

                    /** @throws void */
                    #[Override]
                    public function getBrandname(): string | null
                    {
                        return null;
                    }

                    /** @throws void */
                    #[Override]
                    public function getKey(): string
                    {
                        return '';
                    }
                };
            }

            /**
             * @return array{factory: class-string|null, search: array<int, string>|null, value?: float|int|string}
             *
             * @throws void
             */
            #[Override]
            public function getVersion(): array
            {
                return [
                    'factory' => null,
                    'search' => null,
                ];
            }

            /** @throws void */
            #[Override]
            public function getKey(): string
            {
                return '';
            }
        };

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
            ->willReturn($os);

        $header = new XUcbrowserDeviceUa(
            value: $ua,
            deviceCode: $deviceCode,
            platformCode: $platformCode,
        );

        self::assertSame($ua, $header->getValue(), sprintf('value mismatch for ua "%s"', $ua));

        self::assertTrue(
            $header->hasDeviceCode(),
        );

        self::assertSame(
            'xxx',
            $header->getDeviceCode(),
        );

        self::assertFalse(
            $header->hasClientCode(),
        );

        self::assertNull(
            $header->getClientCode(),
        );

        self::assertFalse(
            $header->hasClientVersion(),
        );

        self::assertInstanceOf(
            NullVersion::class,
            $header->getClientVersion(),
        );

        self::assertTrue(
            $header->hasPlatformCode(),
        );

        self::assertSame(
            $os,
            $header->getPlatformCode(),
        );

        self::assertFalse(
            $header->hasPlatformVersion(),
        );

        self::assertInstanceOf(
            NullVersion::class,
            $header->getPlatformVersion(),
        );

        self::assertFalse(
            $header->hasEngineCode(),
        );

        try {
            $header->getEngineCode();

            self::fail('Exception expected');
        } catch (NotFoundException) {
            // do nothing
        }

        self::assertFalse(
            $header->hasEngineVersion(),
        );

        self::assertInstanceOf(
            NullVersion::class,
            $header->getEngineVersion(),
        );
    }
}
