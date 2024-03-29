<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2023, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequestTest\Header;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use UaRequest\Header\XUcbrowserDevice;

final class XUcbrowserDeviceTest extends TestCase
{
    /** @throws ExpectationFailedException */
    #[DataProvider('providerUa')]
    public function testData(string $ua, bool $hasDeviceInfo): void
    {
        $header = new XUcbrowserDevice($ua);

        self::assertSame($ua, $header->getValue(), 'header mismatch');
        self::assertSame($hasDeviceInfo, $header->hasDeviceInfo(), 'device info mismatch');
        self::assertFalse($header->hasBrowserInfo(), 'browser info mismatch');
        self::assertFalse($header->hasPlatformInfo(), 'platform info mismatch');
        self::assertFalse($header->hasEngineInfo(), 'engine info mismatch');
    }

    /**
     * @return array<int, array<int, bool|string>>
     *
     * @throws void
     */
    public static function providerUa(): array
    {
        return [
            ['nokia#200', true],
            ['nokia#C2-01', true],
            ['samsung#-GT-C3312', true],
            ['j2me', false],
            ['nokia#501', true],
            ['nokia#C7-00', true],
            ['samsung#-GT-S3850', true],
            ['samsung#-GT-S5250', true],
            ['samsung#-GT-S8600', true],
            ['NOKIA # 6120c', true],
            ['Nokia # E7-00', true],
            ['Jblend', false],
            ['nokia#501s', true],
            ['nokia#503s', true],
            ['nokia#Asha230DualSIM', true],
            ['samsung#-gt-s5380d', true],
            ['samsung#-GT-S5380K', true],
            ['samsung#-GT-S5253', true],
            ['tcl#-C616', true],
            ['maui e800', true],
            ['Opera', false],
        ];
    }
}
