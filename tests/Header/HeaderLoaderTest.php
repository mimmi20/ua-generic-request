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

use BrowserDetector\Loader\NotFoundException;
use PHPUnit\Framework\TestCase;
use UaRequest\Constants;
use UaRequest\Header\HeaderInterface;
use UaRequest\Header\HeaderLoader;

class HeaderLoaderTest extends TestCase
{
    /**
     * @var \UaRequest\Header\HeaderLoader
     */
    private $subject;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->subject = new HeaderLoader();
    }

    /**
     * @return void
     */
    public function testLoadFail(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('the header with name "unknown header" was not found');

        $this->subject->load('unknown header');
    }

    /**
     * @return void
     */
    public function testLoadOk(): void
    {
        $value  = 'header-value';
        $header = $this->subject->load(Constants::HEADER_USERAGENT, $value);

        self::assertInstanceOf(HeaderInterface::class, $header);
        self::assertSame($value, $header->getValue());
    }

    /**
     * @return void
     */
    public function testHas(): void
    {
        self::assertTrue($this->subject->has(Constants::HEADER_USERAGENT));
    }
}
