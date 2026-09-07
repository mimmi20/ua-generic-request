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
use UaRequest\Header\SecChUaBitness;
use UaResult\Bits\Bits;
use UaResult\Device\Architecture;
use UaResult\Device\FormFactor;

use function sprintf;

final class SecChUaBitnessTest extends TestCase
{
    /** @throws Exception */
    #[DataProvider(methodName: 'providerUa')]
    public function testData(string $ua, bool $hasBits, Bits $bits): void
    {
        $secChUaBitness = new SecChUaBitness($ua);

        self::assertSame($ua, $secChUaBitness->getValue(), sprintf('value mismatch for ua "%s"', $ua));
        self::assertSame(
            $ua,
            $secChUaBitness->getNormalizedValue(),
            sprintf('value mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaBitness->hasDeviceArchitecture(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            Architecture::unknown,
            $secChUaBitness->getDeviceArchitecture(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaBitness->hasDeviceFormFactor(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            [FormFactor::unknown],
            $secChUaBitness->getDeviceFormFactor(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            $hasBits,
            $secChUaBitness->hasDeviceBitness(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            $bits,
            $secChUaBitness->getDeviceBitness(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaBitness->hasDeviceIsMobile(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaBitness->getDeviceIsMobile(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaBitness->hasDeviceCode(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaBitness->getDeviceCode(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaBitness->hasDeviceIsWow64(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaBitness->getDeviceIsWow64(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaBitness->hasClientCode(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaBitness->getClientCode(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaBitness->hasClientVersion(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertInstanceOf(
            NullVersion::class,
            $secChUaBitness->getClientVersion(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaBitness->hasPlatformCode(),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );

        try {
            $secChUaBitness->getPlatformCode();

            self::fail('Exception expected');
        } catch (NotFoundException) {
            // do nothing
        }

        self::assertFalse(
            $secChUaBitness->hasPlatformVersion(),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );
        self::assertInstanceOf(
            NullVersion::class,
            $secChUaBitness->getPlatformVersionWithOs(Os::unknown),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaBitness->hasEngineCode(),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );

        try {
            $secChUaBitness->getEngineCode();

            self::fail('Exception expected');
        } catch (NotFoundException) {
            // do nothing
        }

        self::assertFalse(
            $secChUaBitness->hasEngineVersion(),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );
        self::assertInstanceOf(
            NullVersion::class,
            $secChUaBitness->getEngineVersionWithEngine(Engine::unknown),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );
    }

    /**
     * @return array<int, list<Bits|bool|string>>
     *
     * @throws void
     */
    public static function providerUa(): array
    {
        return [
            ['64', true, Bits::sixtyfour],
            ['"64"', true, Bits::sixtyfour],
            ['""', false, Bits::unknown],
            ['"22"', false, Bits::unknown],
            ['"abc"', false, Bits::unknown],
        ];
    }
}
