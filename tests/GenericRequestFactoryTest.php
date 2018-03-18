<?php
/**
 * This file is part of the ua-generic-request package.
 *
 * Copyright (c) 2015-2018, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace UaRequestTest;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use UaRequest\Constants;
use UaRequest\GenericRequest;
use UaRequest\GenericRequestFactory;

class GenericRequestFactoryTest extends TestCase
{
    /**
     * @var \UaRequest\GenericRequestFactory
     */
    private $object;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->object = new GenericRequestFactory();
    }

    /**
     * @return void
     */
    public function testCreateRequestFromArray(): void
    {
        $userAgent = 'testUA';
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $expected = new GenericRequest($header);

        $result = $this->object->createRequestFromArray($header);

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertEquals($expected, $result);
        self::assertSame($userAgent, $result->getBrowserUserAgent());
    }

    /**
     * @return void
     */
    public function testCreateRequestFromEmptyHeaders(): void
    {
        $header = [];

        $expected = new GenericRequest($header);

        $result = $this->object->createRequestFromArray($header);

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertEquals($expected, $result);
        self::assertSame('', $result->getBrowserUserAgent());
    }

    /**
     * @return void
     */
    public function testCreateRequestFromString(): void
    {
        $userAgent = 'testUA';
        $header    = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $expected = new GenericRequest($header);

        $result = $this->object->createRequestFromString($userAgent);

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertEquals($expected, $result);
        self::assertSame($userAgent, $result->getBrowserUserAgent());
    }

    /**
     * @throws \ReflectionException
     *
     * @return void
     */
    public function testCreateRequestFromPsr7Message(): void
    {
        $userAgent = 'testUA';
        $deviceUa  = 'testDeviceUa';
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
            Constants::HEADER_DEVICE_UA      => $deviceUa,
        ];

        $messageHeaders = [
            Constants::HEADER_HTTP_USERAGENT => [$userAgent],
            Constants::HEADER_DEVICE_UA      => [$deviceUa],
        ];

        $expected = new GenericRequest($headers);

        $message = $this->createMock(MessageInterface::class);
        $message->expects(self::once())
            ->method('getHeaders')
            ->willReturn($messageHeaders);

        $result = $this->object->createRequestFromPsr7Message($message);

        self::assertInstanceOf('\UaRequest\GenericRequest', $result);
        self::assertEquals($expected, $result);
        self::assertSame($userAgent, $result->getBrowserUserAgent());
        self::assertSame($deviceUa, $result->getDeviceUserAgent());
    }
}
