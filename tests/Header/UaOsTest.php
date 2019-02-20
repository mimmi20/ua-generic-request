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

class UaOsTest extends TestCase
{
    /**
     * @return void
     */
    public function testData(): void
    {
        $ua     = 'Windows CE (Smartphone) - Version 5.2';
        $header = new UaOs($ua);

        self::assertSame($ua, $header->getValue());
        self::assertFalse($header->hasDeviceInfo());
        self::assertFalse($header->hasBrowserInfo());
        self::assertTrue($header->hasPlatformInfo());
        self::assertFalse($header->hasEngineInfo());
    }
}
