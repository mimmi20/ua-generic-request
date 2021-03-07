<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2021, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace UaRequestTest\Header;

use PHPUnit\Framework\TestCase;
use UaRequest\Header\XPuffinUa;

final class XPuffinUaTest extends TestCase
{
    /**
     * @dataProvider providerUa
     *
     * @param string $ua
     * @param bool   $hasDeviceInfo
     * @param bool   $hasPlatformInfo
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testData(string $ua, bool $hasDeviceInfo, bool $hasPlatformInfo): void
    {
        $header = new XPuffinUa($ua);

        self::assertSame($ua, $header->getValue(), 'header mismatch');
        self::assertSame($hasDeviceInfo, $header->hasDeviceInfo(), 'device info mismatch');
        self::assertFalse($header->hasBrowserInfo(), 'browser info mismatch');
        self::assertSame($hasPlatformInfo, $header->hasPlatformInfo(), 'platform info mismatch');
        self::assertFalse($header->hasEngineInfo(), 'engine info mismatch');
    }

    /**
     * @return array[]
     */
    public function providerUa(): array
    {
        return [
            ['iPhone OS/iPad4,1/1536x2048', true, true],
            ['Android/D6503/1080x1776', true, true],
            ['Android/SM-G900F/1080x1920', true, true],
            ['Android/Nexus 10/1600x2464', true, true],
            ['Android/SAMSUNG-SM-N910A/1440x2560', true, true],
            ['Android/bq Edison/1280x752', true, true],
            ['iPhone OS/iPhone6,1/320x568', true, true],
            ['Android/LenovoA3300-HV/600x976', true, true],
            ['Android/SM-T310/1280x800', true, true],
            ['iPhone OS/iPhone7,1/1242x2208', true, true],
            ['iPhone OS/iPad4,1/1024x768', true, true],
            ['iPhone OS/iPhone 3GS/320x480', true, true],
            ['fake OS/iPhone 3GS/320x480', true, false],
        ];
    }
}
