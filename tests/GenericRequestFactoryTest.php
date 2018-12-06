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
use UaRequest\Constants;
use UaRequest\GenericRequest;
use UaRequest\GenericRequestFactory;
use Zend\Diactoros\ServerRequestFactory;

final class GenericRequestFactoryTest extends TestCase
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
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $expected = new GenericRequest(ServerRequestFactory::fromGlobals($headers));

        $result = $this->object->createRequestFromArray($headers);

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertEquals($expected, $result);
        self::assertSame($userAgent, $result->getBrowserUserAgent());
    }

    /**
     * @return void
     */
    public function testCreateRequestFromEmptyHeaders(): void
    {
        $headers = [];

        $expected = new GenericRequest(ServerRequestFactory::fromGlobals($headers));

        $result = $this->object->createRequestFromArray($headers);

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
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $expected = new GenericRequest(ServerRequestFactory::fromGlobals($headers));

        $result = $this->object->createRequestFromString($userAgent);

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertEquals($expected, $result);
        self::assertSame($userAgent, $result->getBrowserUserAgent());
    }

    /**
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

        $expected = new GenericRequest(ServerRequestFactory::fromGlobals($headers));

        $result = $this->object->createRequestFromPsr7Message(ServerRequestFactory::fromGlobals($headers));

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertEquals($expected, $result);
        self::assertSame($userAgent, $result->getBrowserUserAgent());
        self::assertSame($deviceUa, $result->getDeviceUserAgent());
    }

    /**
     * @return void
     */
    public function testCreateRequestFromInvalidString(): void
    {
        $userAgent = "Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; SQQ52974OEM044059604956O~{┬ªM~┬UZUY\nPM)";
        $resultUa  = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; SQQ52974OEM044059604956O~{┬ªM~┬UZUY-PM)';
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $resultUa,
        ];

        $expected = new GenericRequest(ServerRequestFactory::fromGlobals($headers));

        $result = $this->object->createRequestFromString($userAgent);

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertEquals($expected, $result);
        self::assertSame($resultUa, $result->getBrowserUserAgent());
    }

    /**
     * @return void
     */
    public function testCreateRequestFromInvalidArray(): void
    {
        $userAgent = "Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; SQQ52974OEM044059604956O~{┬ªM~┬UZUY\nPM)";
        $headers   = [
            Constants::HEADER_HTTP_USERAGENT => $userAgent,
        ];

        $resultUa        = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0; SQQ52974OEM044059604956O~{┬ªM~┬UZUY-PM)';
        $expectedHeaders = [
            Constants::HEADER_HTTP_USERAGENT => $resultUa,
        ];

        $expected = new GenericRequest(ServerRequestFactory::fromGlobals($expectedHeaders));

        $result = $this->object->createRequestFromArray($headers);

        self::assertInstanceOf(GenericRequest::class, $result);
        self::assertEquals($expected, $result);
        self::assertSame($resultUa, $result->getBrowserUserAgent());
    }
}
