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
use UaRequest\Header\XRequestedWith;

final class XRequestedWithTest extends TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @dataProvider providerUa
     */
    public function testData(string $ua, bool $hasBrowserInfo): void
    {
        $header = new XRequestedWith($ua);

        self::assertSame($ua, $header->getValue(), 'header mismatch');
        self::assertFalse($header->hasDeviceInfo(), 'device info mismatch');
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
            ['com.browser2345', true],
            ['this.is.a.fake.id.to.test.unknown.ids', false],
            ['me.android.browser', true],
            ['com.android.browser', true],
            ['com.mx.browser', true],
            ['mobi.mgeek.TunnyBrowser', true],
            ['com.tencent.mm', true],
            ['com.asus.browser', true],
            ['com.UCMobile.lab', true],
            ['com.oupeng.browser', true],
            ['com.lenovo.browser', true],
            ['derek.iSurf', true],
            ['com.aliyun.mobile.browser', true],
            ['XMLHttpRequest', false],
        ];
    }
}
