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

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use UaRequest\Header\XOperaminiPhone;

final class XOperaminiPhoneTest extends TestCase
{
    /**
     * @throws ExpectationFailedException
     *
     * @dataProvider providerUa
     */
    public function testData(string $ua, bool $hasDeviceInfo): void
    {
        $header = new XOperaminiPhone($ua);

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
            ['RIM # BlackBerry 8520', true],
            ['Samsung # GT-S8500', true],
            ['Samsung # GT-i8000', true],
            ['RIM # BlackBerry 8900', true],
            ['HTC # Touch Pro/T7272/TyTn III', true],
            ['Android #', false],
            ['? # ?', false],
            ['BlackBerry # 9700', true],
            ['Blackberry # 9300', true],
            ['Samsung # SCH-U380', true],
            ['Pantech # TXT8045', true],
            ['ZTE # F-450', true],
            ['LG # VN271', true],
            ['Casio # C781', true],
            ['Samsung # SCH-U485', true],
            ['Pantech # CDM8992', true],
            ['LG # VN530', true],
            ['Samsung # SCH-U680', true],
            ['Pantech # CDM8999', true],
            ['Apple # iPhone', true],
            ['Motorola # A1000', true],
            ['HTC # HD2', true],
        ];
    }
}
