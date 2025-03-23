<?php

/**
 * This file is part of the mimmi20/ua-generic-request package.
 *
 * Copyright (c) 2015-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace UaRequestTest;

use Laminas\Diactoros\ServerRequestFactory;
use Override;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use UaRequest\GenericRequestInterface;
use UaRequest\Header\HeaderInterface;
use UaRequest\Header\HeaderLoaderInterface;
use UaRequest\RequestBuilder;

use function assert;
use function sprintf;

final class RequestBuilderTest extends TestCase
{
    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testBuildRequestFromUaString(): void
    {
        $useragent = 'testagent';

        $headerLoader = $this->createMock(HeaderLoaderInterface::class);

        $object = new RequestBuilder($headerLoader);

        $result = $object->buildRequest($useragent);
        assert(
            $result instanceof GenericRequestInterface,
            sprintf(
                '$result should be an instance of %s, but is %s',
                GenericRequestInterface::class,
                $result::class,
            ),
        );

        self::assertInstanceOf(GenericRequestInterface::class, $result);

        $headers = $result->getHeaders();

        self::assertCount(1, $headers);
        self::assertArrayHasKey('user-agent', $headers);
        self::assertInstanceOf(HeaderInterface::class, $headers['user-agent']);
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testBuildRequestFromHeaderArray(): void
    {
        $useragent = 'testagent';

        $headerLoader = $this->createMock(HeaderLoaderInterface::class);

        $object = new RequestBuilder($headerLoader);

        $result = $object->buildRequest(
            ['user-agent' => $useragent, 1 => $useragent . "\r" . $useragent, 'x-test' => $useragent . "\r\n" . $useragent],
        );
        assert(
            $result instanceof GenericRequestInterface,
            sprintf(
                '$result should be an instance of %s, but is %s',
                GenericRequestInterface::class,
                $result::class,
            ),
        );

        self::assertInstanceOf(GenericRequestInterface::class, $result);

        $headers = $result->getHeaders();

        self::assertCount(1, $headers);
        self::assertArrayHasKey('user-agent', $headers);
        self::assertInstanceOf(HeaderInterface::class, $headers['user-agent']);
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testBuildRequestFromMessage(): void
    {
        $useragent = 'testagent';

        $headerLoader = $this->createMock(HeaderLoaderInterface::class);

        $object = new RequestBuilder($headerLoader);

        $message = ServerRequestFactory::fromGlobals(
            ['HTTP_USER_AGENT' => $useragent, 'HTTP_X_TEST' => $useragent . "\r\n " . $useragent],
        );

        $result = $object->buildRequest($message);
        assert(
            $result instanceof GenericRequestInterface,
            sprintf(
                '$result should be an instance of %s, but is %s',
                GenericRequestInterface::class,
                $result::class,
            ),
        );

        self::assertInstanceOf(GenericRequestInterface::class, $result);

        $headers = $result->getHeaders();

        self::assertCount(1, $headers);
        self::assertArrayHasKey('user-agent', $headers);
        self::assertInstanceOf(HeaderInterface::class, $headers['user-agent']);
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testBuildRequestFromRequest(): void
    {
        $headerLoader = $this->createMock(HeaderLoaderInterface::class);

        $object = new RequestBuilder($headerLoader);

        $request = new class () implements GenericRequestInterface {
            /**
             * @return array<non-empty-string, HeaderInterface>
             *
             * @throws void
             */
            #[Override]
            public function getHeaders(): array
            {
                return [];
            }

            /** @throws void */
            #[Override]
            public function getHash(): string
            {
                return '';
            }
        };

        $result = $object->buildRequest($request);
        assert(
            $result instanceof GenericRequestInterface,
            sprintf(
                '$result should be an instance of %s, but is %s',
                GenericRequestInterface::class,
                $result::class,
            ),
        );

        self::assertSame($request, $result);
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testBuildRequestFromHeaderArray2(): void
    {
        $useragent     = '+Simple Browser';
        $requestedWith = 'com.massimple.nacion.parana.es';

        $headerLoader = $this->createMock(HeaderLoaderInterface::class);

        $object = new RequestBuilder($headerLoader);

        $result = $object->buildRequest(
            [
                'user-agent' => $useragent,
                1 => $useragent . "\r" . $useragent,
                'x-test' => $useragent . "\r\n" . $useragent,
                'http-x-requested-with' => $requestedWith,
            ],
        );
        assert(
            $result instanceof GenericRequestInterface,
            sprintf(
                '$result should be an instance of %s, but is %s',
                GenericRequestInterface::class,
                $result::class,
            ),
        );

        self::assertInstanceOf(GenericRequestInterface::class, $result);

        $headers = $result->getHeaders();

        self::assertCount(2, $headers);
        self::assertArrayHasKey('user-agent', $headers);
        self::assertArrayHasKey('x-requested-with', $headers);
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testBuildRequestFromHeaderArray3(): void
    {
        $useragent     = '+Simple Browser';
        $requestedWith = 'com.massimple.nacion.parana.es';

        $headerLoader = $this->createMock(HeaderLoaderInterface::class);

        $object = new RequestBuilder($headerLoader);

        $result = $object->buildRequest(
            [
                'user-agent' => $useragent,
                1 => $useragent . "\r" . $useragent,
                '' => $useragent . "\r\n" . $useragent,
                'http_x-requested-with' => $requestedWith,
            ],
        );
        assert(
            $result instanceof GenericRequestInterface,
            sprintf(
                '$result should be an instance of %s, but is %s',
                GenericRequestInterface::class,
                $result::class,
            ),
        );

        self::assertInstanceOf(GenericRequestInterface::class, $result);

        $headers = $result->getHeaders();

        self::assertCount(2, $headers);
        self::assertArrayHasKey('user-agent', $headers);
        self::assertArrayHasKey('x-requested-with', $headers);
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testBuildRequestFromHeaderArray4(): void
    {
        $useragent     = '+Simple Browser';
        $requestedWith = 'com.massimple.nacion.parana.es';

        $headerLoader = $this->createMock(HeaderLoaderInterface::class);

        $object = new RequestBuilder($headerLoader);

        $result = $object->buildRequest(
            [
                'user-agent' => $useragent,
                'http+x-requested-with' => $requestedWith,
            ],
        );
        assert(
            $result instanceof GenericRequestInterface,
            sprintf(
                '$result should be an instance of %s, but is %s',
                GenericRequestInterface::class,
                $result::class,
            ),
        );

        self::assertInstanceOf(GenericRequestInterface::class, $result);

        $headers = $result->getHeaders();

        self::assertCount(1, $headers);
        self::assertArrayHasKey('user-agent', $headers);
    }
}
