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
use UaRequest\Header\XOriginalUseragent;

final class XOriginalUseragentTest extends TestCase
{
    private const UA = 'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/4A93 Safari/419.3';

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testData(): void
    {
        $header = new XOriginalUseragent(self::UA);

        self::assertSame(self::UA, $header->getValue(), 'header mismatch');
        self::assertTrue($header->hasDeviceInfo(), 'device info mismatch');
        self::assertTrue($header->hasBrowserInfo(), 'browser info mismatch');
        self::assertTrue($header->hasPlatformInfo(), 'platform info mismatch');
        self::assertTrue($header->hasEngineInfo(), 'engine info mismatch');
    }
}
