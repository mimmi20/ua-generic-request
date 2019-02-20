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

class UseragentTest extends TestCase
{
    /**
     * @return void
     */
    public function testData(): void
    {
        $ua = 'Windows CE (Smartphone) - Version 5.2';

        $header = new Useragent($ua);

        self::assertSame($ua, $header->getValue());
        self::assertTrue($header->hasDeviceInfo());
        self::assertTrue($header->hasBrowserInfo());
        self::assertTrue($header->hasPlatformInfo());
        self::assertTrue($header->hasEngineInfo());
    }
}
