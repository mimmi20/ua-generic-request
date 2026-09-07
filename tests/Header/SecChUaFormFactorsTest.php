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

use BrowserDetector\Version\NullVersion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use UaData\Engine;
use UaData\Os;
use UaRequest\Exception\NotFoundException;
use UaRequest\Header\SecChUaFormFactors;
use UaResult\Bits\Bits;
use UaResult\Device\Architecture;
use UaResult\Device\FormFactor;

use function sprintf;

final class SecChUaFormFactorsTest extends TestCase
{
    /**
     * @param list<FormFactor> $factors
     *
     * @throws Exception
     */
    #[DataProvider(methodName: 'providerUa')]
    public function testData(string $ua, bool $hasFormFactor, array $factors): void
    {
        $secChUaFormFactors = new SecChUaFormFactors($ua);

        self::assertSame(
            $ua,
            $secChUaFormFactors->getValue(),
            sprintf('value mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            $ua,
            $secChUaFormFactors->getNormalizedValue(),
            sprintf('value mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaFormFactors->hasDeviceArchitecture(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            Architecture::unknown,
            $secChUaFormFactors->getDeviceArchitecture(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            $hasFormFactor,
            $secChUaFormFactors->hasDeviceFormFactor(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            $factors,
            $secChUaFormFactors->getDeviceFormFactor(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaFormFactors->hasDeviceBitness(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertSame(
            Bits::unknown,
            $secChUaFormFactors->getDeviceBitness(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaFormFactors->hasDeviceIsMobile(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaFormFactors->getDeviceIsMobile(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaFormFactors->hasDeviceCode(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaFormFactors->getDeviceCode(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaFormFactors->hasDeviceIsWow64(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaFormFactors->getDeviceIsWow64(),
            sprintf('device info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaFormFactors->hasClientCode(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertNull(
            $secChUaFormFactors->getClientCode(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaFormFactors->hasClientVersion(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertInstanceOf(
            NullVersion::class,
            $secChUaFormFactors->getClientVersion(),
            sprintf('browser info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaFormFactors->hasPlatformCode(),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );

        try {
            $secChUaFormFactors->getPlatformCode();

            self::fail('Exception expected');
        } catch (NotFoundException) {
            // do nothing
        }

        self::assertFalse(
            $secChUaFormFactors->hasPlatformVersion(),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );
        self::assertInstanceOf(
            NullVersion::class,
            $secChUaFormFactors->getPlatformVersionWithOs(Os::unknown),
            sprintf('platform info mismatch for ua "%s"', $ua),
        );
        self::assertFalse(
            $secChUaFormFactors->hasEngineCode(),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );

        try {
            $secChUaFormFactors->getEngineCode();

            self::fail('Exception expected');
        } catch (NotFoundException) {
            // do nothing
        }

        self::assertFalse(
            $secChUaFormFactors->hasEngineVersion(),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );
        self::assertInstanceOf(
            NullVersion::class,
            $secChUaFormFactors->getEngineVersionWithEngine(Engine::unknown),
            sprintf('engine info mismatch for ua "%s"', $ua),
        );
    }

    /**
     * @return list<list<bool|list<FormFactor>|string>>
     *
     * @throws void
     */
    public static function providerUa(): array
    {
        return [
            ['"Mobile"', true, [FormFactor::mobile]],
            ['"Desktop"', true, [FormFactor::desktop]],
            ['"Tablet"', true, [FormFactor::tablet]],
            ['"Automotive"', true, [FormFactor::automotive]],
            ['"Xr"', true, [FormFactor::xr]],
            ['""', false, []],
            ['"EInk", "Watch"', true, [FormFactor::eink, FormFactor::watch]],
            ['"abc"', false, [FormFactor::unknown]],
        ];
    }
}
