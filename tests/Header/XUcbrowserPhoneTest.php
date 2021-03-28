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

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use UaRequest\Header\XUcbrowserPhone;

final class XUcbrowserPhoneTest extends TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @dataProvider providerUa
     */
    public function testData(string $ua, bool $hasDeviceInfo, bool $hasBrowserInfo): void
    {
        $header = new XUcbrowserPhone($ua);

        self::assertSame($ua, $header->getValue(), 'header mismatch');
        self::assertSame($hasDeviceInfo, $header->hasDeviceInfo(), 'device info mismatch');
        self::assertSame($hasBrowserInfo, $header->hasBrowserInfo(), 'browser info mismatch');
        self::assertFalse($header->hasPlatformInfo(), 'platform info mismatch');
        self::assertFalse($header->hasEngineInfo(), 'engine info mismatch');
    }

    /**
     * @return array<int, array<int, bool|string>>
     */
    public function providerUa(): array
    {
        return [
            ['maui browser', false, true],
            ['nokia701', true, false],
            ['sunmicro', false, false],
            ['nokiac3-01', true, false],
            ['nokia305', true, false],
            ['gt-s5233s', true, false],
            ['sonyericssonj108i', true, false],
        ];
    }
}
