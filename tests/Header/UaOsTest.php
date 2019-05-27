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
use UaRequest\Header\UaOs;

final class UaOsTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testData(): void
    {
        $ua     = 'Windows CE (Smartphone) - Version 5.2';
        $header = new UaOs($ua);

        static::assertSame($ua, $header->getValue(), 'header mismatch');
        static::assertFalse($header->hasDeviceInfo(), 'device info mismatch');
        static::assertFalse($header->hasBrowserInfo(), 'browser info mismatch');
        static::assertTrue($header->hasPlatformInfo(), 'platform info mismatch');
        static::assertFalse($header->hasEngineInfo(), 'engine info mismatch');
    }
}
