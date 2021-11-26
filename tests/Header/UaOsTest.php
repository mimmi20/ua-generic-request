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
use UaRequest\Header\UaOs;

final class UaOsTest extends TestCase
{
    private const UA = 'Windows CE (Smartphone) - Version 5.2';

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testData(): void
    {
        $header = new UaOs(self::UA);

        self::assertSame(self::UA, $header->getValue(), 'header mismatch');
        self::assertFalse($header->hasDeviceInfo(), 'device info mismatch');
        self::assertFalse($header->hasBrowserInfo(), 'browser info mismatch');
        self::assertTrue($header->hasPlatformInfo(), 'platform info mismatch');
        self::assertFalse($header->hasEngineInfo(), 'engine info mismatch');
    }
}
