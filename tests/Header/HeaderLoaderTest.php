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

use BrowserDetector\Loader\NotFoundException;
use PHPUnit\Framework\TestCase;
use UaRequest\Constants;
use UaRequest\Header\HeaderInterface;
use UaRequest\Header\HeaderLoader;

final class HeaderLoaderTest extends TestCase
{
    /** @var \UaRequest\Header\HeaderLoader */
    private $subject;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->subject = new HeaderLoader();
    }

    /**
     * @throws \BrowserDetector\Loader\NotFoundException
     *
     * @return void
     */
    public function testLoadFail(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('the header with name "unknown header" was not found');

        $this->subject->load('unknown header');
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \BrowserDetector\Loader\NotFoundException
     *
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testHas(): void
    {
        self::assertTrue($this->subject->has(Constants::HEADER_USERAGENT));
    }
}
