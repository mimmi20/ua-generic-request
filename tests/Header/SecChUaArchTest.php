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
use UaRequest\Header\SecChUaArch;
use UaResult\Bits\Bits;
use UaResult\Device\Architecture;
use UaResult\Device\FormFactor;

use function sprintf;

final class SecChUaArchTest extends TestCase
{
    /** @throws Exception */
    #[DataProvider(methodName: 'providerUa')]
    public function testData(string $ua, bool $hasArch, Architecture $arch): void
    {
        $secChUaArch = new SecChUaArch($ua);

        self::assertSame($ua, $secChUaArch->getValue(), sprintf('value mismatch for ua "%s"', $ua));
        self::assertSame(
            $ua,
            $secChUaArch->getNormalizedValue(),
            sprintf('value mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            $hasArch,
            $secChUaArch->hasDeviceArchitecture(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            $arch,
            $secChUaArch->getDeviceArchitecture(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaArch->hasDeviceFormFactor(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            [FormFactor::unknown],
            $secChUaArch->getDeviceFormFactor(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaArch->hasDeviceBitness(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            Bits::unknown,
            $secChUaArch->getDeviceBitness(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaArch->hasDeviceIsMobile(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaArch->getDeviceIsMobile(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaArch->hasDeviceCode(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaArch->getDeviceCode(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaArch->hasDeviceIsWow64(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaArch->getDeviceIsWow64(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaArch->hasClientCode(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaArch->getClientCode(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaArch->hasClientVersion(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertInstanceOf(
            NullVersion::class,
            $secChUaArch->getClientVersion(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaArch->hasPlatformCode(),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );

        try {
            $secChUaArch->getPlatformCode();

            self::fail('Exception expected');
        } catch (NotFoundException) {
            // do nothing
        }

        self::assertFalse(
            $secChUaArch->hasPlatformVersion(),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );
        self::assertInstanceOf(
            NullVersion::class,
            $secChUaArch->getPlatformVersionWithOs(Os::unknown),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaArch->hasEngineCode(),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );

        try {
            $secChUaArch->getEngineCode();

            self::fail('Exception expected');
        } catch (NotFoundException) {
            // do nothing
        }

        self::assertFalse(
            $secChUaArch->hasEngineVersion(),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );
        self::assertInstanceOf(
            NullVersion::class,
            $secChUaArch->getEngineVersionWithEngine(Engine::unknown),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );
    }

    /**
     * @return list<list<Architecture|bool|string>>
     *
     * @throws void
     */
    public static function providerUa(): array
    {
        return [
            ['arm', true, Architecture::arm],
            ['"arm"', true, Architecture::arm],
            ['"x86"', true, Architecture::x86],
            ['""', false, Architecture::unknown],
            ['"abc"', false, Architecture::unknown],
        ];
    }
}
