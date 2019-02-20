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
use UaRequest\Header\XOriginalUseragent;

class XOriginalUseragentTest extends TestCase
{
    /**
     * @return void
     */
    public function testData(): void
    {
        $ua = 'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/4A93 Safari/419.3';

        $header = new XOriginalUseragent($ua);

        self::assertSame($ua, $header->getValue());
        self::assertTrue($header->hasDeviceInfo());
        self::assertTrue($header->hasBrowserInfo());
        self::assertTrue($header->hasPlatformInfo());
        self::assertTrue($header->hasEngineInfo());
    }
}
