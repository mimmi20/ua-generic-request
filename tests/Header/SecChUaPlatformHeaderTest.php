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

namespace Header;

use Override;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use UaData\CompanyInterface;
use UaData\OsInterface;
use UaParser\DeviceCodeInterface;
use UaParser\PlatformCodeInterface;
use UaRequest\Exception\NotFoundException;
use UaRequest\Header\SecChUaPlatformHeader;

use function sprintf;

final class SecChUaPlatformHeaderTest extends TestCase
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

        $deviceCode = $this->createMock(DeviceCodeInterface::class);
        $deviceCode
            ->expects(self::once())
            ->method('hasDeviceCode')
            ->with($ua)
            ->willReturn(false);
        $deviceCode
            ->expects(self::once())
            ->method('getDeviceCode')
            ->with($ua)
            ->willReturn(null);

        $header = new SecChUaPlatformHeader($ua, $platformCode, $deviceCode);

        self::assertSame($ua, $header->getValue(), sprintf('value mismatch for ua "%s"', $ua));

        self::assertTrue(
            $header->hasPlatformCode(),
        );

        self::assertSame(
            $os,
            $header->getPlatformCode(),
        );

        self::assertFalse(
            $header->hasDeviceCode(),
        );

        self::assertNull(
            $header->getDeviceCode(),
        );
    }
}
