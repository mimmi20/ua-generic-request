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
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use UaData\CompanyInterface;
use UaData\Engine;
use UaData\Os;
use UaData\OsInterface;
use UaParser\DeviceCodeInterface;
use UaParser\PlatformCodeInterface;
use UaRequest\Exception\NotFoundException;
use UaRequest\Header\XPuffinUa;

use function sprintf;

final class XPuffinUaTest extends TestCase
{
    /**
     * @throws Exception
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
            ->willReturn(value: true);
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
            ->willReturn(value: true);
        $platformCode
            ->expects(self::once())
            ->method('getPlatformCode')
            ->with($ua)
            ->willReturn($os);

        $xPuffinUa = new XPuffinUa(value: $ua, deviceCode: $deviceCode, platformCode: $platformCode);

        self::assertSame($ua, $xPuffinUa->getValue(), sprintf('value mismatch for ua "%s"', $ua));

        self::assertTrue(
            $xPuffinUa->hasDeviceCode(),
        );

        self::assertSame(
            'xxx',
            $xPuffinUa->getDeviceCode(),
        );

        self::assertFalse(
            $xPuffinUa->hasClientCode(),
        );

        self::assertNull(
            $xPuffinUa->getClientCode(),
        );

        self::assertFalse(
            $xPuffinUa->hasClientVersion(),
        );

        self::assertInstanceOf(
            NullVersion::class,
            $xPuffinUa->getClientVersion(),
        );

        self::assertTrue(
            $xPuffinUa->hasPlatformCode(),
        );

        self::assertSame(
            $os,
            $xPuffinUa->getPlatformCode(),
        );

        self::assertFalse(
            $xPuffinUa->hasPlatformVersion(),
        );

        self::assertInstanceOf(
            NullVersion::class,
            $xPuffinUa->getPlatformVersionWithOs(Os::unknown),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );

        self::assertFalse(
            $xPuffinUa->hasEngineCode(),
        );

        try {
            $xPuffinUa->getEngineCode();

            self::fail('Exception expected');
        } catch (NotFoundException) {
            // do nothing
        }

        self::assertFalse(
            $xPuffinUa->hasEngineVersion(),
        );

        self::assertInstanceOf(
            NullVersion::class,
            $xPuffinUa->getEngineVersionWithEngine(Engine::unknown),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );
    }
}
