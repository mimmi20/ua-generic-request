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

class XUcbrowserPhoneTest extends TestCase
{
    /**
     * @dataProvider providerUa
     *
     * @param string $ua
     * @param bool   $hasDeviceInfo
     *
     * @return void
     */
    public function testData(string $ua, bool $hasDeviceInfo): void
    {
        $header = new XUcbrowserPhone($ua);

        self::assertSame($ua, $header->getValue());
        self::assertSame($hasDeviceInfo, $header->hasDeviceInfo());
        self::assertFalse($header->hasBrowserInfo());
        self::assertFalse($header->hasPlatformInfo());
        self::assertFalse($header->hasEngineInfo());
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
