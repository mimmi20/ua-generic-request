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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use UaData\Engine;
use UaData\Os;
use UaRequest\Exception\NotFoundException;
use UaRequest\Header\SecChUaMobile;
use UaResult\Bits\Bits;
use UaResult\Device\Architecture;
use UaResult\Device\FormFactor;

use function sprintf;

final class SecChUaMobileTest extends TestCase
{
    /** @throws Exception */
    #[DataProvider(methodName: 'providerUa')]
    public function testData(string $ua, bool | null $isMobile): void
    {
        $secChUaMobile = new SecChUaMobile($ua);

        self::assertSame($ua, $secChUaMobile->getValue(), sprintf('value mismatch for ua "%s"', $ua));
        self::assertSame(
            $ua,
            $secChUaMobile->getNormalizedValue(),
            sprintf('value mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaMobile->hasDeviceArchitecture(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            Architecture::unknown,
            $secChUaMobile->getDeviceArchitecture(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaMobile->hasDeviceFormFactor(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            [FormFactor::unknown],
            $secChUaMobile->getDeviceFormFactor(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaMobile->hasDeviceBitness(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            Bits::unknown,
            $secChUaMobile->getDeviceBitness(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertTrue(
            $secChUaMobile->hasDeviceIsMobile(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            $isMobile,
            $secChUaMobile->getDeviceIsMobile(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaMobile->hasDeviceCode(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaMobile->getDeviceCode(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaMobile->hasDeviceIsWow64(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaMobile->getDeviceIsWow64(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaMobile->hasClientCode(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaMobile->getClientCode(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaMobile->hasClientVersion(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertInstanceOf(
            NullVersion::class,
            $secChUaMobile->getClientVersion(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaMobile->hasPlatformCode(),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );

        try {
            $secChUaMobile->getPlatformCode();

            self::fail('Exception expected');
        } catch (NotFoundException) {
            // do nothing
        }

        self::assertFalse(
            $secChUaMobile->hasPlatformVersion(),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );
        self::assertInstanceOf(
            NullVersion::class,
            $secChUaMobile->getPlatformVersionWithOs(Os::unknown),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaMobile->hasEngineCode(),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );

        try {
            $secChUaMobile->getEngineCode();

            self::fail('Exception expected');
        } catch (NotFoundException) {
            // do nothing
        }

        self::assertFalse(
            $secChUaMobile->hasEngineVersion(),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );
        self::assertInstanceOf(
            NullVersion::class,
            $secChUaMobile->getEngineVersionWithEngine(Engine::unknown),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );
    }

    /**
     * @return array<int, array<int, bool|string>>
     *
     * @throws void
     */
    public static function providerUa(): array
    {
        return [
            ['?0', false],
            ['"?0"', false],
            ['"?1"', true],
            ['?1', true],
            ['1', true],
            ['0', false],
            ['', false],
            ['""', false],
        ];
    }
}
