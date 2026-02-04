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
use BrowserDetector\Version\Version;
use Override;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use UaData\CompanyInterface;
use UaData\Engine;
use UaData\EngineInterface;
use UaParser\ClientCodeInterface;
use UaParser\ClientVersionInterface;
use UaParser\EngineCodeInterface;
use UaParser\EngineVersionInterface;
use UaRequest\Header\ClientHeader;

use function sprintf;

final class ClientHeaderTest extends TestCase
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
        $versionEngine = new Version('10');

        $engine = new class () implements EngineInterface {
            /** @throws void */
            #[Override]
            public function getName(): string | null
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
            ->expects(self::exactly(2))
            ->method('hasClientCode')
            ->with($ua)
            ->willReturn(true);
        $clientCode
            ->expects(self::once())
            ->method('getClientCode')
            ->with($ua)
            ->willReturn('xxx');

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
            ->willReturn($versionClient);

        $engineCode = $this->createMock(EngineCodeInterface::class);
        $engineCode
            ->expects(self::exactly(2))
            ->method('hasEngineCode')
            ->with($ua)
            ->willReturn(true);
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
            ->willReturn(true);
        $engineVersion
            ->expects(self::once())
            ->method('getEngineVersionWithEngine')
            ->with($ua, Engine::unknown)
            ->willReturn($versionEngine);

        $header = new ClientHeader(
            value: $ua,
            clientCode: $clientCode,
            clientVersion: $clientVersion,
            engineCode: $engineCode,
            engineVersion: $engineVersion,
        );

        self::assertSame($ua, $header->getValue(), sprintf('value mismatch for ua "%s"', $ua));

        self::assertTrue(
            $header->hasClientCode(),
        );

        self::assertSame(
            'xxx',
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
            $header->hasEngineCode(),
        );

        self::assertSame(
            $engine,
            $header->getEngineCode(),
        );

        self::assertTrue(
            $header->hasEngineVersion(),
        );

        self::assertSame(
            $versionEngine,
            $header->getEngineVersionWithEngine(Engine::unknown),
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

        $versionClient = new Version('4');
        $versionEngine = new Version('10');

        $engine = new class () implements EngineInterface {
            /** @throws void */
            #[Override]
            public function getName(): string | null
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
            ->expects(self::exactly(2))
            ->method('hasClientCode')
            ->with($ua)
            ->willReturn(false);
        $clientCode
            ->expects(self::once())
            ->method('getClientCode')
            ->with($ua)
            ->willReturn('xxx');

        $clientVersion = $this->createMock(ClientVersionInterface::class);
        $clientVersion
            ->expects(self::never())
            ->method('hasClientVersion');
        $clientVersion
            ->expects(self::once())
            ->method('getClientVersion')
            ->with($ua)
            ->willReturn($versionClient);

        $engineCode = $this->createMock(EngineCodeInterface::class);
        $engineCode
            ->expects(self::exactly(2))
            ->method('hasEngineCode')
            ->with($ua)
            ->willReturn(true);
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
            ->willReturn(true);
        $engineVersion
            ->expects(self::once())
            ->method('getEngineVersionWithEngine')
            ->with($ua, Engine::unknown)
            ->willReturn($versionEngine);

        $header = new ClientHeader(
            value: $ua,
            clientCode: $clientCode,
            clientVersion: $clientVersion,
            engineCode: $engineCode,
            engineVersion: $engineVersion,
        );

        self::assertSame($ua, $header->getValue(), sprintf('value mismatch for ua "%s"', $ua));

        self::assertFalse(
            $header->hasClientCode(),
        );

        self::assertSame(
            'xxx',
            $header->getClientCode(),
        );

        self::assertFalse(
            $header->hasClientVersion(),
        );

        self::assertSame(
            $versionClient,
            $header->getClientVersion(),
        );

        self::assertTrue(
            $header->hasEngineCode(),
        );

        self::assertSame(
            $engine,
            $header->getEngineCode(),
        );

        self::assertTrue(
            $header->hasEngineVersion(),
        );

        self::assertSame(
            $versionEngine,
            $header->getEngineVersionWithEngine(Engine::unknown),
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

        $versionClient = new Version('4');
        $versionEngine = new Version('10');

        $engine = new class () implements EngineInterface {
            /** @throws void */
            #[Override]
            public function getName(): string | null
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
            ->expects(self::exactly(2))
            ->method('hasClientCode')
            ->with($ua)
            ->willReturn(true);
        $clientCode
            ->expects(self::once())
            ->method('getClientCode')
            ->with($ua)
            ->willReturn('xxx');

        $clientVersion = $this->createMock(ClientVersionInterface::class);
        $clientVersion
            ->expects(self::once())
            ->method('hasClientVersion')
            ->with($ua)
            ->willReturn(false);
        $clientVersion
            ->expects(self::once())
            ->method('getClientVersion')
            ->with($ua)
            ->willReturn($versionClient);

        $engineCode = $this->createMock(EngineCodeInterface::class);
        $engineCode
            ->expects(self::exactly(2))
            ->method('hasEngineCode')
            ->with($ua)
            ->willReturn(true);
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
            ->willReturn(true);
        $engineVersion
            ->expects(self::once())
            ->method('getEngineVersionWithEngine')
            ->with($ua, Engine::unknown)
            ->willReturn($versionEngine);

        $header = new ClientHeader(
            value: $ua,
            clientCode: $clientCode,
            clientVersion: $clientVersion,
            engineCode: $engineCode,
            engineVersion: $engineVersion,
        );

        self::assertSame($ua, $header->getValue(), sprintf('value mismatch for ua "%s"', $ua));

        self::assertTrue(
            $header->hasClientCode(),
        );

        self::assertSame(
            'xxx',
            $header->getClientCode(),
        );

        self::assertFalse(
            $header->hasClientVersion(),
        );

        self::assertSame(
            $versionClient,
            $header->getClientVersion(),
        );

        self::assertTrue(
            $header->hasEngineCode(),
        );

        self::assertSame(
            $engine,
            $header->getEngineCode(),
        );

        self::assertTrue(
            $header->hasEngineVersion(),
        );

        self::assertSame(
            $versionEngine,
            $header->getEngineVersionWithEngine(Engine::unknown),
        );
    }
}
