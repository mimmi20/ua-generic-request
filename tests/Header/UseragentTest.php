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
use UaRequest\Header\Useragent;

final class UseragentTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testData(): void
    {
        $ua = 'Windows CE (Smartphone) - Version 5.2';

        $header = new Useragent($ua);

        static::assertSame($ua, $header->getValue(), 'header mismatch');
        static::assertTrue($header->hasDeviceInfo(), 'device info mismatch');
        static::assertTrue($header->hasBrowserInfo(), 'browser info mismatch');
        static::assertTrue($header->hasPlatformInfo(), 'platform info mismatch');
        static::assertTrue($header->hasEngineInfo(), 'engine info mismatch');
    }
}
