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

use BrowserDetector\Version\Exception\NotNumericException;
use BrowserDetector\Version\NullVersion;
use BrowserDetector\Version\Version;
use Override;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use UaData\CompanyInterface;
use UaData\Engine;
use UaData\Os;
use UaData\OsInterface;
use UaParser\ClientCodeInterface;
use UaParser\ClientVersionInterface;
use UaParser\PlatformCodeInterface;
use UaRequest\Exception\NotFoundException;
use UaRequest\Header\XRequestedWith;

use function sprintf;

final class XRequestedWithTest extends TestCase
{
    /**
     * @throws Exception
     * @throws NotFoundException
     * @throws NotNumericException
     */
    public function testData(): void
    {
        $ua = 'Microsoft Windows NT 8.10.14219.0;4.0.30508.0;HUAWEI;HUAWEI W2-U00;4a1b5d7105057f0c0208d83c699276ff92cedbff;2.5.0.12';

        $version = new Version('4');

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

        $clientCode = $this->createMock(ClientCodeInterface::class);
        $clientCode
            ->expects(self::once())
            ->method('hasClientCode')
            ->with($ua)
            ->willReturn(value: true);
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
            ->willReturn(value: true);
        $clientVersion
            ->expects(self::once())
            ->method('getClientVersion')
            ->with($ua, null)
            ->willReturn($version);

        $platformCode = $this->createMock(PlatformCodeInterface::class);
        $platformCode
            ->expects(self::once())
            ->method('hasPlatformCode')
            ->with($ua)
            ->willReturn(value: true);
        $platformCode
            ->expects(self::once())
            ->method('getPlatformCode')
            ->with($ua, null)
            ->willReturn($os);

        $xRequestedWith = new XRequestedWith(
            value: $ua,
            clientCode: $clientCode,
            clientVersion: $clientVersion,
            platformCode: $platformCode,
        );

        self::assertSame($ua, $xRequestedWith->getValue(), sprintf('value mismatch for ua "%s"', $ua));

        self::assertFalse(
            $xRequestedWith->hasDeviceCode(),
        );

        self::assertNull(
            $xRequestedWith->getDeviceCode(),
        );

        self::assertTrue(
            $xRequestedWith->hasClientCode(),
        );

        self::assertSame(
            'yyy',
            $xRequestedWith->getClientCode(),
        );

        self::assertTrue(
            $xRequestedWith->hasClientVersion(),
        );

        self::assertSame(
            $version,
            $xRequestedWith->getClientVersion(),
        );

        self::assertTrue(
            $xRequestedWith->hasPlatformCode(),
        );

        self::assertSame(
            $os,
            $xRequestedWith->getPlatformCode(),
        );

        self::assertFalse(
            $xRequestedWith->hasPlatformVersion(),
        );

        self::assertInstanceOf(
            NullVersion::class,
            $xRequestedWith->getPlatformVersionWithOs(Os::unknown),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );

        self::assertFalse(
            $xRequestedWith->hasEngineCode(),
        );

        try {
            $xRequestedWith->getEngineCode();

            self::fail('Exception expected');
        } catch (NotFoundException) {
            // do nothing
        }

        self::assertFalse(
            $xRequestedWith->hasEngineVersion(),
        );

        self::assertInstanceOf(
            NullVersion::class,
            $xRequestedWith->getEngineVersionWithEngine(Engine::unknown),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );
    }
}
