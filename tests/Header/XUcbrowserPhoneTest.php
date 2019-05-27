<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2019, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace UaRequestTest\Header;

use PHPUnit\Framework\TestCase;
use UaRequest\Header\XUcbrowserPhone;

final class XUcbrowserPhoneTest extends TestCase
{
    /**
     * @dataProvider providerUa
     *
     * @param string $ua
     * @param bool   $hasDeviceInfo
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testData(string $ua, bool $hasDeviceInfo): void
    {
        $header = new XUcbrowserPhone($ua);

        static::assertSame($ua, $header->getValue(), 'header mismatch');
        static::assertSame($hasDeviceInfo, $header->hasDeviceInfo(), 'device info mismatch');
        static::assertFalse($header->hasBrowserInfo(), 'browser info mismatch');
        static::assertFalse($header->hasPlatformInfo(), 'platform info mismatch');
        static::assertFalse($header->hasEngineInfo(), 'engine info mismatch');
    }

    /**
     * @return array[]
     */
    public function providerUa(): array
    {
        return [
            ['nokia701', true],
            ['sunmicro', true],
            ['nokiac3-01', true],
            ['nokia305', true],
            ['gt-s5233s', true],
            ['sonyericssonj108i', true],
        ];
    }
}
