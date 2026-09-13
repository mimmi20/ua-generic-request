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

use BrowserDetector\Version\Exception\NotNumericException;
use BrowserDetector\Version\Version;
use Override;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use UaData\Engine;
use UaData\EngineInterface;
use UaData\Os;
use UaData\OsInterface;
use UaParser\ClientCodeInterface;
use UaParser\ClientVersionInterface;
use UaParser\DeviceCodeInterface;
use UaParser\EngineCodeInterface;
use UaParser\EngineVersionInterface;
use UaParser\PlatformCodeInterface;
use UaParser\PlatformVersionInterface;
use UaRequest\Exception\NotFoundException;
use UaRequest\Header\FullHeader;

use function sprintf;

final class FullHeaderTest extends TestCase
{
    /**
     * @throws Exception
     * @throws NotNumericException
     * @throws NotFoundException
     */
    public function testData(): void
    {
        $ua = 'Microsoft Windows NT 8.10.14219.0;4.0.30508.0;HUAWEI;HUAWEI W2-U00;4a1b5d7105057f0c0208d83c699276ff92cedbff;2.5.0.12';

        $engine = new class () implements EngineInterface {
            /** @throws void */
            #[Override]
            public function getName(): string | null
            {
                return null;
            }

            /** @throws void */
            #[Override]
            public function getManufacturer(): string
            {
                return '';
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
            public function getManufacturer(): string
            {
                return '';
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

        $versionClient = new Version('4');
        $versionOs     = new Version('6');
        $versionEngine = new Version('10');

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
            ->willReturn($versionClient);

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

        $platformVersion = $this->createMock(PlatformVersionInterface::class);
        $platformVersion
            ->expects(self::once())
            ->method('hasPlatformVersion')
            ->with($ua)
            ->willReturn(value: true);
        $platformVersion
            ->expects(self::once())
            ->method('getPlatformVersionWithOs')
            ->with($ua, Os::unknown)
            ->willReturn($versionOs);

        $engineCode = $this->createMock(EngineCodeInterface::class);
        $engineCode
            ->expects(self::once())
            ->method('hasEngineCode')
            ->with($ua)
            ->willReturn(value: true);
        $engineCode
            ->expects(self::once())
            ->method('getEngineCode')
            ->with($ua)
            ->willReturn($engine);

        $engineVersion = $this->createMock(EngineVersionInterface::class);
        $engineVersion
            ->expects(self::once())
            ->method('hasEngineVersion')
            ->with($ua)
            ->willReturn(value: true);
        $engineVersion
            ->expects(self::once())
            ->method('getEngineVersionWithEngine')
            ->with($ua, Engine::unknown)
            ->willReturn($versionEngine);

        $fullHeader = new FullHeader(
            value: $ua,
            deviceCode: $deviceCode,
            clientCode: $clientCode,
            clientVersion: $clientVersion,
            platformCode: $platformCode,
            platformVersion: $platformVersion,
            engineCode: $engineCode,
            engineVersion: $engineVersion,
        );

        self::assertSame($ua, $fullHeader->getValue(), sprintf('value mismatch for ua "%s"', $ua));

        self::assertTrue(
            $fullHeader->hasDeviceCode(),
        );

        self::assertSame(
            'xxx',
            $fullHeader->getDeviceCode(),
        );

        self::assertTrue(
            $fullHeader->hasClientCode(),
        );

        self::assertSame(
            'yyy',
            $fullHeader->getClientCode(),
        );

        self::assertTrue(
            $fullHeader->hasClientVersion(),
        );

        self::assertSame(
            $versionClient,
            $fullHeader->getClientVersion(),
        );

        self::assertTrue(
            $fullHeader->hasPlatformCode(),
        );

        self::assertSame(
            $os,
            $fullHeader->getPlatformCode(),
        );

        self::assertTrue(
            $fullHeader->hasPlatformVersion(),
        );

        self::assertSame(
            $versionOs,
            $fullHeader->getPlatformVersionWithOs(Os::unknown),
        );

        self::assertTrue(
            $fullHeader->hasEngineCode(),
        );

        self::assertSame(
            $engine,
            $fullHeader->getEngineCode(),
        );

        self::assertTrue(
            $fullHeader->hasEngineVersion(),
        );

        self::assertSame(
            $versionEngine,
            $fullHeader->getEngineVersionWithEngine(Engine::unknown),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );
    }
}
